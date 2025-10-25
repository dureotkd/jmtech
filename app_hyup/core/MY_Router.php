<?php

/**
 * @todo : 라우터 확장 (컨트롤러 디렉토리 뎁스 제한 때문에)
 */
class MY_Router extends CI_Router
{


    /**
     * Validates the supplied segments.  Attempts to determine the path to
     * the controller.
     *
     * @access    private
     * @param    array
     * @return    array
     */
    function _validate_request($segments)
    {

        # Array ( [0] => React [1] => okemsAnalysis )
        # Array ( [0] => React [1] => okemsAnalysis )

        foreach ($segments as $_key => $seg) {

            $segments[$_key] = ucfirst($seg);
        }

        /**
         * React를 위한 라우터 설정
         */
        if (!empty($segments[0]) && $segments[0] === 'React') {

            if (count($segments) > 2) {

                $this->_append_directory($segments[0]);

                $reactRouter = array($segments[1]);

                return $reactRouter;
            }
        }

        // Does the requested controller exist in the root folder? (첫글자가 대문자가 아닐때만)
        if (file_exists(APPPATH . 'controllers/' . $this->fetch_directory() . $segments[0] . EXT)) {
            return $segments;
        }

        // Is the controller in a sub-folder?
        if (is_dir(APPPATH . 'controllers/' . $this->fetch_directory() . $segments[0])) {

            // Set the directory and remove it from the segment array
            $this->_append_directory($segments[0]);
            $segments = array_slice($segments, 1);

            if (count($segments) > 0) {

                // Does the requested controller exist in the sub-folder?
                if (!file_exists(APPPATH . 'controllers/' . $this->fetch_directory() . ucfirst($segments[0]) . EXT)) {
                    return $this->_validate_request($segments);
                }
            } else {
                $this->set_class($this->default_controller);
                $this->set_method('index');

                // Does the default controller exist in the sub-folder?
                if (!file_exists(APPPATH . 'controllers/' . $this->fetch_directory() . $this->default_controller . EXT)) {
                    $this->directory = '';
                    return array();
                }
            }

            return $segments;
        }

        if ($segments[0] == 'Naverd70cb7328240989e5ab3b303e19d643e.html') {
            echo 'naver-site-verification: naverd70cb7328240989e5ab3b303e19d643e.html';
            exit;
        }

        // Can't find the requested controller...
        show_404($segments[0]);
    }

    /**
     *  Append the directory name
     *
     * @access  public
     * @param   string
     * @return  void
     */
    function _append_directory($dir)
    {
        $this->directory .= $dir . '/';
    }
}
