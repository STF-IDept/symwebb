## WebbFileBundle

### Classes

1. **Location.php**.  Provides calls to determine the root directory and the upload directory based on the kernal's location. 

  This class has the following functions:

	1. getWebRoot() - returns the web directory location (eg /home/user/web).
	2. getDir($subdir) - returns the upload directory location, relative to the web directory location, and appends $subdir (eg uploads/$subdir). 

  The class is dependent on the following settings being configured in config.yml:
 
	1. webb_file.web_dir - folder name for web directory (eg web). 
	2. webb_file.upload_dir - folder name for upload directory (eg uploads). 

  This class is available as a service:

	1. $location = $this->container->get('webb_file_location');
	 
### Entities

1. **Image.php**.  Entity for loading and saving images. 

  This Entity has the following public functions:

	1. setWebRoot($web_root) - sets root directory
	2. setUploadDir($upload_dir) - sets upload directory
	3. getWebPath() - gets web location of image
	4. preUpload() - reformats image name - called before entity is updated
	5. upload() - saves image - called after entity is updated
	6. removeUpload() - unlink file - called after entity is removed
	7. getId() - get image ID
	8. getFile() - get file entity
	9. setName($name) - sets file name
	10. setFolder($folder) - sets folder within upload path