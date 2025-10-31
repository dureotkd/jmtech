<?
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

        // 압축파일 (원하면 포함)
        'zip',
        'rar',
        '7z'
    ];

    // 파일 업로드 함수
    public function upload($fileInputName, $uploadDirectory, $maxSizeMB = 5, $allowedExtensions = null)
    {
        $allowedExtensions = $allowedExtensions ?? $this->allowedExtensions;

        $result = [
            'status' => 'error',  // 기본 상태를 'error'로 설정
            'message' => '',      // 메시지
            'fileName' => '',     // 업로드된 파일 이름
            'filePath' => ''      // 업로드된 파일 경로
        ];

        // 파일이 올바르게 업로드되었는지 체크
        if (isset($_FILES[$fileInputName])) {
            $file = $_FILES[$fileInputName];


            // 파일 업로드 오류 체크
            if ($file['error'] !== UPLOAD_ERR_OK) {
                $result['message'] = "파일 업로드에 실패했습니다.";
                return $result;  // 오류 메시지를 포함하여 리턴
            }

            // 파일 크기 체크 (기본 5MB 이하)
            if ($file['size'] > $maxSizeMB * 1024 * 1024) { // MB 단위로 파일 크기 제한
                $result['message'] = "파일 크기는 {$maxSizeMB}MB 이하로 업로드해 주세요.";
                return $result;  // 오류 메시지를 포함하여 리턴
            }

            // 파일 확장자 체크
            $fileExtension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

            if (!in_array($fileExtension, $allowedExtensions)) {
                $result['message'] = "허용된 확장자는 " . implode(', ', $allowedExtensions) . " 입니다.";
                return $result;  // 오류 메시지를 포함하여 리턴
            }

            // 파일 이름 생성 (파일 이름 충돌 방지)
            $fileName = uniqid() . '.' . $fileExtension;
            $uploadPath = $uploadDirectory . "/" . $fileName;
            $fullUploadPath = rtrim($_SERVER['DOCUMENT_ROOT'], '/') . '/' . ltrim($uploadDirectory, '/');

            // 업로드 폴더가 없으면 생성
            if (!is_dir($fullUploadPath)) {
                mkdir($fullUploadPath, 0777, true);
            }

            $serverFilePath = $_SERVER['DOCUMENT_ROOT'] . $uploadPath;

            // 파일 업로드
            if (move_uploaded_file($file['tmp_name'], $serverFilePath)) {
                $result['status'] = 'success';  // 성공 상태
                $result['message'] = "파일 업로드 성공";
                $result['fileName'] = $fileName;
                $result['originalFileName'] = $file['name'];
                $result['fileSize'] = $file['size'];
                $result['filePath'] = $uploadPath;
                $result['fileSrc'] = 도메인 .  $uploadPath;  // 서버 경로 추가
            } else {
                $result['message'] = "파일 업로드 중 오류가 발생했습니다." . "서버 경로: " . $serverFilePath;
            }
        } else {
            $result['message'] = "파일이 전송되지 않았습니다.";
        }
        return $result;  // 결과 배열 리턴
    }

    public function upload_multiple($fileInputName, $uploadDirectory, $maxSizeMB = 5, $allowedExtensions = null)
    {
        $allowedExtensions = $allowedExtensions ?? $this->allowedExtensions;
        $results = [];

        // 파일 배열 체크
        if (isset($_FILES[$fileInputName]) && is_array($_FILES[$fileInputName]['name'])) {
            $fileCount = count($_FILES[$fileInputName]['name']);

            for ($i = 0; $i < $fileCount; $i++) {
                $file = [
                    'name' => $_FILES[$fileInputName]['name'][$i],
                    'type' => $_FILES[$fileInputName]['type'][$i],
                    'tmp_name' => $_FILES[$fileInputName]['tmp_name'][$i],
                    'error' => $_FILES[$fileInputName]['error'][$i],
                    'size' => $_FILES[$fileInputName]['size'][$i],
                ];

                // 단일 업로드 함수 재활용
                $_FILES['__single_temp'] = $file;
                $result = $this->upload('__single_temp', $uploadDirectory, $maxSizeMB, $allowedExtensions);
                $results[] = $result;
            }

            // 임시 필드 제거
            unset($_FILES['__single_temp']);
        } else {
            $results[] = [
                'status' => 'error',
                'message' => '파일 배열이 올바르지 않거나 전송되지 않았습니다.',
            ];
        }

        return $results;  // 전체 결과 리턴
    }
}
