<?php
class PostController {
    private $pdo;


    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // Method to handle creating a video post
public function createVideoPost($userId, $postVideo) {
    try {
        if ($postVideo['error'] !== UPLOAD_ERR_OK) {
            throw new Exception("Error uploading video: " . $this->getUploadError($postVideo['error']));
        }

        // Upload the video and get the path
        $videoPath = $this->uploadVideo($postVideo);
        
        $query = "INSERT INTO posts (post_video, post_type, user_id, created_at) 
                  VALUES (:post_video, 'video', :user_id, NOW())";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':post_video', $videoPath);
        $stmt->bindParam(':user_id', $userId);
        $stmt->execute();
    } catch (Exception $e) {
        throw new Exception("Error creating video post: " . $e->getMessage());
    }
}

// Method to handle creating an image post
public function createImagePost($userId, $postImage) {
    try {
        if ($postImage['error'] !== UPLOAD_ERR_OK) {
            throw new Exception("Error uploading image: " . $this->getUploadError($postImage['error']));
        }

        // Upload the image and get the path
        $imagePath = $this->uploadImage($postImage);
        
        $query = "INSERT INTO posts (post_image, post_type, user_id, created_at) 
                  VALUES (:post_image, 'image', :user_id, NOW())";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':post_image', $imagePath);
        $stmt->bindParam(':user_id', $userId);
        $stmt->execute();
    } catch (Exception $e) {
        throw new Exception("Error creating image post: " . $e->getMessage());
    }
}

// Helper method to translate upload error codes into meaningful messages
private function getUploadError($errorCode) {
    $uploadErrors = [
        UPLOAD_ERR_OK => 'No error',
        UPLOAD_ERR_INI_SIZE => 'The uploaded file exceeds the upload_max_filesize directive in php.ini',
        UPLOAD_ERR_FORM_SIZE => 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form',
        UPLOAD_ERR_PARTIAL => 'The uploaded file was only partially uploaded',
        UPLOAD_ERR_NO_FILE => 'No file was uploaded',
        UPLOAD_ERR_NO_TMP_DIR => 'Missing a temporary folder',
        UPLOAD_ERR_CANT_WRITE => 'Failed to write file to disk',
        UPLOAD_ERR_EXTENSION => 'A PHP extension stopped the file upload'
    ];

    return isset($uploadErrors[$errorCode]) ? $uploadErrors[$errorCode] : 'Unknown error';
}

// Method to handle image upload
private function uploadImage($postImage) {
    $uploadDir = __DIR__ . '/../../public/uploads/images/';
    
    // Ensure the upload directory exists
    if (!is_dir($uploadDir)) {
        if (!mkdir($uploadDir, 0777, true)) {
            throw new Exception("Failed to create directory for images.");
        }
    }
    
    $imageName = uniqid() . '_' . basename($postImage['name']);
    $imagePath = '/public/uploads/images/' . $imageName;
    
    if (move_uploaded_file($postImage['tmp_name'], $uploadDir . '/' . $imageName)) {
        return $imagePath;
    } else {
        throw new Exception("Failed to upload image.");
    }
}

// Method to handle video upload
private function uploadVideo($postVideo) {
    $uploadDir = __DIR__ . '/../../public/uploads/videos/';
    
    if (!is_dir($uploadDir)) {
        if (!mkdir($uploadDir, 0777, true)) {
            throw new Exception("Failed to create directory for videos.");
        }
    }

    $videoName = uniqid() . '_' . basename($postVideo['name']);
    $videoPath = '/public/uploads/videos/' . $videoName;

    if (move_uploaded_file($postVideo['tmp_name'], $uploadDir . '/' . $videoName)) {
        return $videoPath;
    } else {
        throw new Exception("Failed to upload video.");
    }
}

}
?>