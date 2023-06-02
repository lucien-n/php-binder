<?php

class User
{
    private $id;
    private $uuid;
    private $username;
    private $passwordHash;
    private $email;
    private $gender;
    private $likedGender;
    private $image;
    private $age;
    private $bio;
    private $createdAt;
    private $updatedAt;
    private $lastSeen;

    public function __construct($id, $uuid, $username, $passwordHash, $email, $gender, $likedGender, $image, $age, $bio, $createdAt, $updatedAt, $lastSeen)
    {
        $this->id = $id;
        $this->uuid = $uuid;
        $this->username = $username;
        $this->passwordHash = $passwordHash;
        $this->email = $email;
        $this->gender = $gender;
        $this->likedGender = $likedGender;
        $this->image = $image;
        $this->age = $age;
        $this->bio = $bio;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
        $this->lastSeen = $lastSeen;
    }




	/**
	 * @return mixed
	 */
	public function getId() {
		return $this->id;
	}
	
	/**
	 * @param mixed $id 
	 * @return self
	 */
	public function setId($id): self {
		$this->id = $id;
		return $this;
	}
	
	/**
	 * @return mixed
	 */
	public function getUuid() {
		return $this->uuid;
	}
	
	/**
	 * @param mixed $uuid 
	 * @return self
	 */
	public function setUuid($uuid): self {
		$this->uuid = $uuid;
		return $this;
	}
	
	/**
	 * @return mixed
	 */
	public function getUsername() {
		return $this->username;
	}
	
	/**
	 * @param mixed $username 
	 * @return self
	 */
	public function setUsername($username): self {
		$this->username = $username;
		return $this;
	}
	
	/**
	 * @return mixed
	 */
	public function getPasswordHash() {
		return $this->passwordHash;
	}
	
	/**
	 * @param mixed $passwordHash 
	 * @return self
	 */
	public function setPasswordHash($passwordHash): self {
		$this->passwordHash = $passwordHash;
		return $this;
	}
	
	/**
	 * @return mixed
	 */
	public function getEmail() {
		return $this->email;
	}
	
	/**
	 * @param mixed $email 
	 * @return self
	 */
	public function setEmail($email): self {
		$this->email = $email;
		return $this;
	}
	
	/**
	 * @return mixed
	 */
	public function getGender() {
		return $this->gender;
	}
	
	/**
	 * @param mixed $gender 
	 * @return self
	 */
	public function setGender($gender): self {
		$this->gender = $gender;
		return $this;
	}
	
	/**
	 * @return mixed
	 */
	public function getLikedGender() {
		return $this->likedGender;
	}
	
	/**
	 * @param mixed $likedGender 
	 * @return self
	 */
	public function setLikedGender($likedGender): self {
		$this->likedGender = $likedGender;
		return $this;
	}
	
	/**
	 * @return mixed
	 */
	public function getImage() {
		return $this->image;
	}
	
	/**
	 * @param mixed $image 
	 * @return self
	 */
	public function setImage($image): self {
		$this->image = $image;
		return $this;
	}
	
	/**
	 * @return mixed
	 */
	public function getAge() {
		return $this->age;
	}
	
	/**
	 * @param mixed $age 
	 * @return self
	 */
	public function setAge($age): self {
		$this->age = $age;
		return $this;
	}
	
	/**
	 * @return mixed
	 */
	public function getBio() {
		return $this->bio;
	}
	
	/**
	 * @param mixed $bio 
	 * @return self
	 */
	public function setBio($bio): self {
		$this->bio = $bio;
		return $this;
	}
	
	/**
	 * @return mixed
	 */
	public function getCreatedAt() {
		return $this->createdAt;
	}
	
	/**
	 * @param mixed $createdAt 
	 * @return self
	 */
	public function setCreatedAt($createdAt): self {
		$this->createdAt = $createdAt;
		return $this;
	}
	
	/**
	 * @return mixed
	 */
	public function getUpdatedAt() {
		return $this->updatedAt;
	}
	
	/**
	 * @param mixed $updatedAt 
	 * @return self
	 */
	public function setUpdatedAt($updatedAt): self {
		$this->updatedAt = $updatedAt;
		return $this;
	}
	
	/**
	 * @return mixed
	 */
	public function getLastSeen() {
		return $this->lastSeen;
	}
	
	/**
	 * @param mixed $lastSeen 
	 * @return self
	 */
	public function setLastSeen($lastSeen): self {
		$this->lastSeen = $lastSeen;
		return $this;
	}
}

?>