<?php

namespace classes;

use Exception;

class UploadException extends Exception
{
    public function __construct($code)
    {
        $message = $this->codeToMessage($code);

        parent::__construct($message, $code);
    }

    private function codeToMessage($code)
    {
        switch ( $code )
        {
            case UPLOAD_ERR_INI_SIZE:
                return 'The uploaded file exceeds the upload_max_filesize directive in php.ini';
                break;

            case UPLOAD_ERR_FORM_SIZE:
                return 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form';
                break;

            case UPLOAD_ERR_PARTIAL:
                return 'The uploaded file was only partially uploaded';
                break;

            case UPLOAD_ERR_NO_FILE:
                return 'No file was uploaded';
                break;

            case UPLOAD_ERR_NO_TMP_DIR:
                return 'Missing a temporary folder';
                break;

            case UPLOAD_ERR_CANT_WRITE:
                return 'Failed to write file to disk';
                break;

            case UPLOAD_ERR_EXTENSION:
                return 'File upload stopped by extension';
                break;

            default:
                return 'Unknown upload error with error code #' . $code;
                break;
        }
    }

}
