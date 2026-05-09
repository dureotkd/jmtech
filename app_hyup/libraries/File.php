<?php
class file
{
    protected $allowedExtensions = [
        // 이미지
        'jpg',
        'jpeg',
        'png',
        'gif',
        'bmp',
        'webp',

        // 3D
        'dxf',
        'stl',
        'obj',
        'ply',
        '3ds',
        'dae',
        'fbx',
        'glb',
        'gltf',

        // 문서
        'pdf',
        'hwp',
        'hwpx',
        'doc',
        'docx',
        'xls',
        'xlsx',
        'csv',
        'ppt',
        'pptx',
        'txt',

        // ZIP은 내부 파일까지 검사한 뒤 허용
        'zip'
    ];

    protected $archiveExtensions = [
        'zip'
    ];

    protected $blockedExtensions = [
        'php',
        'php3',
        'php4',
        'php5',
        'php7',
        'php8',
        'phtml',
        'pht',
        'phar',
        'asp',
        'aspx',
        'jsp',
        'jspx',
        'cfm',
        'cgi',
        'pl',
        'py',
        'rb',
        'sh',
        'bash',
        'zsh',
        'ksh',
        'cmd',
        'bat',
        'ps1',
        'psm1',
        'vbs',
        'vbe',
        'js',
        'jse',
        'wsf',
        'hta',
        'exe',
        'msi',
        'dll',
        'com',
        'scr',
        'pif',
        'jar',
        'class',
        'war',
        'ear',
        'apk',
        'ipa',
        'deb',
        'rpm',
        'rar',
        '7z',
        'tar',
        'gz',
        'bz2',
        'xz',
        'iso',
        'dmg',
        'pkg',
        'run',
        'bin',
        'elf',
        'so',
        'dylib',
        'sys',
        'drv',
        'lnk'
    ];

    protected $blockedMimeTypes = [
        'application/x-php',
        'application/x-httpd-php',
        'text/x-php',
        'application/x-msdownload',
        'application/x-msdos-program',
        'application/x-dosexec',
        'application/vnd.microsoft.portable-executable',
        'application/x-executable',
        'application/x-sharedlib',
        'application/java-archive',
        'application/x-java-applet',
        'application/x-shellscript',
        'text/x-shellscript',
        'text/x-python',
        'text/x-perl',
        'text/x-ruby'
    ];

    public function upload($fileInputName, $uploadDirectory, $maxSizeMB = 5, $allowedExtensions = null)
    {
        $allowedExtensions = $allowedExtensions ?? $this->allowedExtensions;
        $allowedExtensions = $this->normalizeAllowedExtensions($allowedExtensions);

        $result = [
            'status' => 'error',
            'message' => '',
            'fileName' => '',
            'filePath' => ''
        ];

        if (!isset($_FILES[$fileInputName])) {
            $result['message'] = "파일이 전송되지 않았습니다.";
            return $result;
        }

        $file = $_FILES[$fileInputName];

        if ($file['error'] !== UPLOAD_ERR_OK) {
            $result['message'] = "파일 업로드에 실패했습니다.";
            return $result;
        }

        if ($file['size'] > $maxSizeMB * 1024 * 1024) {
            $result['message'] = "파일 크기는 {$maxSizeMB}MB 이하로 업로드해 주세요.";
            return $result;
        }

        $fileExtension = $this->normalizeExtension(pathinfo($file['name'], PATHINFO_EXTENSION));
        $validationResult = $this->validateUploadSecurity($file, $fileExtension, $allowedExtensions);

        if ($validationResult !== true) {
            $result['message'] = $validationResult;
            return $result;
        }

        $uploadDirectory = $this->normalizeUploadDirectory($uploadDirectory);
        if ($uploadDirectory === false) {
            $result['message'] = "업로드 경로가 올바르지 않습니다.";
            return $result;
        }

        $fileName = uniqid('', true) . '.' . $fileExtension;
        $uploadPath = rtrim($uploadDirectory, '/') . "/" . $fileName;
        $fullUploadPath = rtrim($_SERVER['DOCUMENT_ROOT'], '/\\') . '/' . ltrim($uploadDirectory, '/');

        $directoryResult = $this->prepareUploadDirectory($fullUploadPath);
        if ($directoryResult !== true) {
            $result['message'] = $directoryResult;
            return $result;
        }

        $this->ensureUploadDirectoryProtection($fullUploadPath);

        $serverFilePath = rtrim($_SERVER['DOCUMENT_ROOT'], '/\\') . $uploadPath;

        if (@move_uploaded_file($file['tmp_name'], $serverFilePath)) {
            $result['status'] = 'success';
            $result['message'] = "파일 업로드 성공";
            $result['fileName'] = $fileName;
            $result['originalFileName'] = $file['name'];
            $result['fileSize'] = $file['size'];
            $result['filePath'] = $uploadPath;
            $result['fileSrc'] = 도메인 .  $uploadPath;
        } else {
            $result['message'] = "파일 저장에 실패했습니다. 업로드 폴더 권한을 확인해 주세요. 서버 경로: " . $serverFilePath;
        }

        return $result;
    }

    public function upload_multiple($fileInputName, $uploadDirectory, $maxSizeMB = 5, $allowedExtensions = null)
    {
        $allowedExtensions = $allowedExtensions ?? $this->allowedExtensions;
        $results = [];

        if (!isset($_FILES[$fileInputName]) || !is_array($_FILES[$fileInputName]['name'])) {
            return [[
                'status' => 'error',
                'message' => '파일 배열이 올바르지 않거나 전송되지 않았습니다.',
            ]];
        }

        foreach ($_FILES[$fileInputName]['name'] as $key => $name) {
            if (empty($name)) {
                continue;
            }

            $_FILES['__single_temp'] = [
                'name' => $_FILES[$fileInputName]['name'][$key],
                'type' => $_FILES[$fileInputName]['type'][$key],
                'tmp_name' => $_FILES[$fileInputName]['tmp_name'][$key],
                'error' => $_FILES[$fileInputName]['error'][$key],
                'size' => $_FILES[$fileInputName]['size'][$key],
            ];

            $results[] = $this->upload('__single_temp', $uploadDirectory, $maxSizeMB, $allowedExtensions);
        }

        unset($_FILES['__single_temp']);

        return $results;
    }

    protected function validateUploadSecurity($file, $fileExtension, $allowedExtensions)
    {
        if ($fileExtension === '') {
            return "확장자가 없는 파일은 업로드할 수 없습니다.";
        }

        if (!in_array($fileExtension, $allowedExtensions, true)) {
            return "허용된 확장자는 " . implode(', ', $allowedExtensions) . " 입니다.";
        }

        if ($this->hasBlockedExtension($fileExtension)) {
            return "PHP 또는 실행 가능한 프로그램 파일은 업로드할 수 없습니다.";
        }

        foreach ($this->getOriginalFileExtensions($file['name'] ?? '') as $extension) {
            if ($this->hasBlockedExtension($extension)) {
                return "PHP 또는 실행 가능한 프로그램 파일은 업로드할 수 없습니다.";
            }
        }

        if (empty($file['tmp_name']) || !is_uploaded_file($file['tmp_name'])) {
            return "정상적인 업로드 파일이 아닙니다.";
        }

        $mimeType = $this->detectMimeType($file['tmp_name']);
        if ($mimeType !== '' && in_array($mimeType, $this->blockedMimeTypes, true)) {
            return "PHP 또는 실행 가능한 프로그램 파일은 업로드할 수 없습니다.";
        }

        if ($this->looksLikeExecutableOrScript($file['tmp_name'])) {
            return "PHP 또는 실행 가능한 프로그램 파일은 업로드할 수 없습니다.";
        }

        if (in_array($fileExtension, $this->archiveExtensions, true)) {
            return $this->validateArchiveContents($file['tmp_name']);
        }

        return true;
    }

    protected function prepareUploadDirectory($directory)
    {
        if (!is_dir($directory) && !@mkdir($directory, 0775, true)) {
            return "업로드 폴더를 생성할 수 없습니다. 서버 권한을 확인해 주세요. 경로: " . $directory;
        }

        if (!is_writable($directory)) {
            @chmod($directory, 0775);
        }

        if (!is_writable($directory)) {
            return "업로드 폴더에 쓰기 권한이 없습니다. 서버 권한을 확인해 주세요. 경로: " . $directory;
        }

        return true;
    }

    protected function normalizeAllowedExtensions($allowedExtensions)
    {
        $allowedExtensions = array_values(array_filter(array_map([$this, 'normalizeExtension'], $allowedExtensions)));
        return array_values(array_diff($allowedExtensions, $this->blockedExtensions));
    }

    protected function normalizeExtension($extension)
    {
        return strtolower(trim((string)$extension, " \t\n\r\0\x0B."));
    }

    protected function hasBlockedExtension($extension)
    {
        return in_array($this->normalizeExtension($extension), $this->blockedExtensions, true);
    }

    protected function getOriginalFileExtensions($fileName)
    {
        $fileName = str_replace('\\', '/', (string)$fileName);
        $fileName = basename($fileName);

        if ($fileName === '' || strpos($fileName, "\0") !== false) {
            return [];
        }

        $parts = explode('.', $fileName);
        array_shift($parts);

        return array_values(array_filter(array_map([$this, 'normalizeExtension'], $parts)));
    }

    protected function detectMimeType($filePath)
    {
        if (!function_exists('finfo_open')) {
            return '';
        }

        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        if (!$finfo) {
            return '';
        }

        $mimeType = finfo_file($finfo, $filePath);
        finfo_close($finfo);

        return is_string($mimeType) ? strtolower($mimeType) : '';
    }

    protected function looksLikeExecutableOrScript($filePath)
    {
        if (!is_readable($filePath)) {
            return true;
        }

        $handle = fopen($filePath, 'rb');
        if (!$handle) {
            return true;
        }

        $bytes = fread($handle, 8192);
        fclose($handle);

        if ($bytes === false || $bytes === '') {
            return false;
        }

        return $this->bytesLookLikeExecutableOrScript($bytes);
    }

    protected function bytesLookLikeExecutableOrScript($bytes)
    {
        foreach (["MZ", "\x7FELF", "\xFE\xED\xFA\xCE", "\xFE\xED\xFA\xCF", "\xCE\xFA\xED\xFE", "\xCF\xFA\xED\xFE", "\xCA\xFE\xBA\xBE"] as $magic) {
            if (strncmp($bytes, $magic, strlen($magic)) === 0) {
                return true;
            }
        }

        if (preg_match('/<\?(?!xml\b)(php|=|\s)/i', $bytes)) {
            return true;
        }

        if (preg_match('/^\s*#!.*\b(php|sh|bash|zsh|ksh|python|perl|ruby|node|powershell)\b/i', $bytes)) {
            return true;
        }

        return false;
    }

    protected function validateArchiveContents($filePath)
    {
        if (!class_exists('ZipArchive')) {
            return "ZIP 파일 검사를 할 수 없어 업로드할 수 없습니다.";
        }

        $zip = new ZipArchive();
        if ($zip->open($filePath) !== true) {
            return "압축 파일이 올바르지 않습니다.";
        }

        for ($i = 0; $i < $zip->numFiles; $i++) {
            $stat = $zip->statIndex($i);
            $entryName = $stat['name'] ?? '';

            if ($entryName === '' || substr($entryName, -1) === '/') {
                continue;
            }

            foreach ($this->getOriginalFileExtensions($entryName) as $extension) {
                if ($this->hasBlockedExtension($extension)) {
                    $zip->close();
                    return "압축 파일 안에 PHP 또는 실행 가능한 프로그램 파일이 포함되어 있습니다.";
                }
            }

            $stream = $zip->getStream($entryName);
            if (!$stream) {
                $zip->close();
                return "압축 파일 내부를 검사할 수 없습니다.";
            }

            $bytes = fread($stream, 8192);
            fclose($stream);

            if ($bytes !== false && $bytes !== '' && $this->bytesLookLikeExecutableOrScript($bytes)) {
                $zip->close();
                return "압축 파일 안에 PHP 또는 실행 가능한 프로그램 파일이 포함되어 있습니다.";
            }
        }

        $zip->close();

        return true;
    }

    protected function normalizeUploadDirectory($uploadDirectory)
    {
        $uploadDirectory = str_replace('\\', '/', (string)$uploadDirectory);

        if ($uploadDirectory === '' || strpos($uploadDirectory, "\0") !== false) {
            return false;
        }

        $uploadDirectory = preg_replace('#/+#', '/', $uploadDirectory);
        $uploadDirectory = '/' . trim($uploadDirectory, '/');

        if ($uploadDirectory === '/' || strpos($uploadDirectory, '/../') !== false || substr($uploadDirectory, -3) === '/..') {
            return false;
        }

        return $uploadDirectory;
    }

    protected function ensureUploadDirectoryProtection($directory)
    {
        $blockedPattern = '\\.(php[0-9]?|phtml|pht|phar|asp|aspx|jsp|jspx|cfm|cgi|pl|py|rb|sh|bash|zsh|ksh|cmd|bat|ps1|psm1|vbs|vbe|js|jse|wsf|hta|exe|msi|dll|com|scr|pif|jar|class|war|ear|apk|rar|7z|tar|gz|bz2|xz|iso|run|bin|elf|so|dylib|sys|drv|lnk)$';
        $rules = implode(PHP_EOL, [
            'Options -Indexes',
            '<IfModule mod_php.c>',
            '    php_flag engine off',
            '</IfModule>',
            '<IfModule mod_headers.c>',
            '    Header set X-Robots-Tag "noindex, nofollow, noarchive, nosnippet, noimageindex"',
            '</IfModule>',
            'RemoveHandler .php .php3 .php4 .php5 .php7 .php8 .phtml .pht .phar .cgi .pl .py .rb .sh .bash .cmd .bat .ps1',
            'RemoveType .php .php3 .php4 .php5 .php7 .php8 .phtml .pht .phar',
            '<FilesMatch "' . $blockedPattern . '">',
            '    Require all denied',
            '    Deny from all',
            '</FilesMatch>',
            ''
        ]);

        $htaccessPath = rtrim($directory, '/\\') . DIRECTORY_SEPARATOR . '.htaccess';

        if (!file_exists($htaccessPath) || file_get_contents($htaccessPath) !== $rules) {
            @file_put_contents($htaccessPath, $rules, LOCK_EX);
        }
    }

    public function download($filePath, $originalFileName = null)
    {
        $file_path = str_replace('//', '/', $_SERVER['DOCUMENT_ROOT'] . $filePath);
        $file_name = $originalFileName ?? basename($file_path);

        if (!file_exists($file_path)) {
            header('HTTP/1.1 404 Not Found');
            exit('파일이 존재하지 않습니다.');
        }

        if (ob_get_level()) {
            ob_end_clean();
        }

        $encoded_filename = rawurlencode($file_name);
        if (preg_match("/MSIE|Trident|Edge/", $_SERVER['HTTP_USER_AGENT'])) {
            header("Content-Disposition: attachment; filename=\"{$encoded_filename}\"");
        } else {
            header("Content-Disposition: attachment; filename*=UTF-8''{$encoded_filename}");
        }

        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($file_path));

        flush();
        readfile($file_path);
        exit;
    }
}
