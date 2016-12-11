<?php
/**
 * Copyright (c) 2016. Spirit-Dev
 * Licensed under GPLv3 GNU License - http://www.gnu.org/licenses/gpl-3.0.html
 *    _             _
 *   /_`_  ._._/___/ | _
 * . _//_//// /   /_.'/_'|/
 *    /
 *
 * Since 2K10 until today
 *
 * Hex            53 70 69 72 69 74 2d 44 65 76
 *
 * By             Jean Bordat
 * Twitter        @Ji_Bay_
 * Mail           <bordat.jean@gmail.com>
 *
 * File           User.php
 * Updated the    11/12/16 16:11
 */

namespace SpiritDev\Bundle\DBoxUserBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Entity\User as BaseUser;
use FR3D\LdapBundle\Model\LdapUserInterface as LdapUserInterface;
use SpiritDev\Bundle\DBoxPortalBundle\Entity\Communication;
use SpiritDev\Bundle\DBoxUserBundle\Lib\Globals;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * User
 *
 * @ORM\Table(name="user")
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Entity(repositoryClass="SpiritDev\Bundle\DBoxUserBundle\Entity\UserRepository")
 * @Vich\Uploadable
 */
class User extends BaseUser implements LdapUserInterface {
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string")
     */
    protected $dn;

    /**
     * @ORM\Column(type="string")
     */
    protected $lastName;

    /**
     * @ORM\Column(type="string")
     */
    protected $firstName;

    /**
     * @ORM\Column(type="string")
     */
    protected $language;

    /**
     * @ORM\Column(name="skip_intro", type="boolean", nullable=true)
     */
    private $skipIntro;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $gitLabId;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $redmineId;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $sonarManaged;

    /**
     * @var object
     *
     * @ORM\ManyToMany(targetEntity="SpiritDev\Bundle\DBoxPortalBundle\Entity\Communication")
     * ORM\JoinTable(name="user_communications",
     *     joinColumns={ORM\JoinColumns(name="user_id", referencedColumnsName="id")},
     *     inverseJoinColumns={ORM\JoinColumns(name="com_id", referencedColumnName="id")}
     * )
     */
    private $viewedCommunications;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updated", type="datetime", nullable=true)
     */
    private $updatedAt;

    /**
     * NOTE: This is not a mapped field of entity metadata, just a simple property.
     *
     * @Vich\UploadableField(mapping="user_avatar", fileNameProperty="imageName")
     * @var File
     */
    private $imageFile;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @var string
     */
    private $imageName;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $nextSyncDate;

    /**
     * User constructor.
     */
    public function __construct() {
        // Construct parent
        parent::__construct();
        // Set communications as an array
        $this->viewedCommunications = new ArrayCollection();
        // Set default user role
        if (empty($this->roles)) {
            $this->roles = Globals::getDefaultRoles();
        }
        // Set dafault user language
        if (empty($this->language)) {
            $this->language = Globals::getDefaultLanguage();
        }
    }

    /**
     * @ORM\PreUpdate()
     */
    public function preUpdate() {
        // Set update date
        $this->updatedAt = new \DateTime();
    }

    /**
     * @return mixed
     */
    public function getDn() {
        return $this->dn;
    }

    /**
     * @param mixed $dn
     */
    public function setDn($dn) {
        $this->dn = $dn;
    }

    /**
     * @return mixed
     */
    public function getId() {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id) {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getFirstName() {
        return $this->firstName;
    }

    /**
     * @param mixed $firstName
     */
    public function setFirstName($firstName) {
        $this->firstName = $firstName;
    }

    /**
     * @return mixed
     */
    public function getLastName() {
        return $this->lastName;
    }

    /**
     * @param mixed $lastName
     */
    public function setLastName($lastName) {
        $this->lastName = $lastName;
    }

    /**
     * @return mixed
     */
    public function getLanguage() {
        return $this->language;
    }

    /**
     * @param mixed $language
     */
    public function setLanguage($language) {
        $this->language = $language;
    }

    /**
     * @return string
     */
    public function getCommonName() {
        return $this->firstName . ' ' . $this->lastName;
    }

    /**
     * @return mixed
     */
    public function getSkipIntro() {
        return $this->skipIntro;
    }

    /**
     * @param mixed $skipIntro
     */
    public function setSkipIntro($skipIntro) {
        $this->skipIntro = $skipIntro;
    }

    /**
     * @return mixed
     */
    public function getGitLabId() {
        return $this->gitLabId;
    }

    /**
     * @param mixed $gitLabId
     */
    public function setGitLabId($gitLabId) {
        $this->gitLabId = $gitLabId;
    }

    /**
     * @return object
     */
    public function getViewedCommunications() {
        return $this->viewedCommunications;
    }

    /**
     * Add comment
     *
     * @param Communication $communication
     *
     * @return object
     */
    public function addViewedCommunication(Communication $communication) {
        $this->viewedCommunications[] = $communication;

        return $this;
    }

    /**
     * Remove comment
     *
     * @param Communication $communication
     */
    public function removeViewedCommunication(Communication $communication) {
        $this->viewedCommunications->removeElement($communication);
    }

    /**
     * @return \DateTime
     */
    public function getExpiresAt() {
        return $this->expiresAt;
    }

    /**
     * @return \DateTime
     */
    public function getCredentialsExpireAt() {
        return $this->credentialsExpireAt;
    }

    /**
     * @return mixed
     */
    public function getRedmineId() {
        return $this->redmineId;
    }

    /**
     * @param mixed $redmineId
     */
    public function setRedmineId($redmineId) {
        $this->redmineId = $redmineId;
    }

    /**
     * @return mixed
     */
    public function getSonarManaged() {
        return $this->sonarManaged;
    }

    /**
     * @param mixed $sonarManaged
     */
    public function setSonarManaged($sonarManaged) {
        $this->sonarManaged = $sonarManaged;
    }

    /**
     * @return \DateTime
     */
    public function getUpdatedAt() {
        return $this->updatedAt;
    }

    /**
     * @param \DateTime $updatedAt
     */
    public function setUpdatedAt($updatedAt) {
        $this->updatedAt = $updatedAt;
    }

    /**
     * @return File
     */
    public function getImageFile() {
        return $this->imageFile;
    }

    /**
     * If manually uploading a file (i.e. not using Symfony Form) ensure an instance
     * of 'UploadedFile' is injected into this setter to trigger the  update. If this
     * bundle's configuration parameter 'inject_on_load' is set to 'true' this setter
     * must be able to accept an instance of 'File' as the bundle will inject one here
     * during Doctrine hydration.
     *
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile $image
     *
     * @return User
     */
    public function setImageFile(File $image = null) {
        $this->imageFile = $image;

        if ($image) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->updatedAt = new \DateTime('now');
        }

        return $this;
    }

    /**
     * @return string
     */
    public function getImageName() {
        return $this->imageName;
    }

    /**
     * @param string $imageName
     */
    public function setImageName($imageName) {
        $this->imageName = $imageName;
    }

    /**
     * @return mixed
     */
    public function getNextSyncDate() {
        return $this->nextSyncDate;
    }

    /**
     * @param mixed $nextSyncDate
     */
    public function setNextSyncDate($nextSyncDate) {
        $this->nextSyncDate = $nextSyncDate;
    }

}