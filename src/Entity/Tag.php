<?php

namespace App\Entity;

use App\Service\GenericFunction;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TagRepository")
 */
class Tag
{
	const PATH_FILE = "photo/tag/";

    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    protected $title;
	
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $photo;

	/**
     * @ORM\ManyToOne(targetEntity="App\Entity\Language")
     */
	protected $language;

   /**
    * @ORM\ManyToMany(targetEntity=Proverb::class, mappedBy="tags")
    */
	protected $proverbs;

	/**
	 * @ORM\Column(name="internationalName", type="string", length=255)
	 */
	private $internationalName;

    /**
     * @ORM\Column(type="string", length=255)
     */
    protected $slug;

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function setTitle($title)
    {
        $this->title = $title;
		$this->setSlug();
    }

    public function getPhoto()
    {
        return $this->photo;
    }

    public function setPhoto($photo)
    {
        $this->photo = $photo;
    }

	public function getLanguage()
	{
		return $this->language;
	}
	
	public function setLanguage($language)
	{
		$this->language = $language;
	}

    /**
     * Set internationalName
     *
     * @param string $internationalName
     */
    public function setInternationalName($internationalName)
    {
        $this->internationalName = $internationalName;
    }

    /**
     * Get internationalName
     *
     * @return internationalName 
     */
    public function getInternationalName()
    {
        return $this->internationalName;
    }

    public function getSlug()
    {
        return $this->slug;
    }

    public function setSlug()
    {
		if(empty($this->slug))
			$this->slug = GenericFunction::slugify($this->title);
    }

   /**
    * Get proverbs
    *
    * @return Doctrine\Common\Collections\Collection
    */
	public function getProverbs()
	{
		return $this->proverbs;
	}
}