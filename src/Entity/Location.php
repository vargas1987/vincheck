<?php
namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class Location
 * @package ShiptorRussiaBundle\Entity
 * @ORM\Table(name="public.location")
 * @ORM\Entity(repositoryClass="App\Repository\LocationRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Location
{
    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\OrderBy({"id" = "DESC"})
     */
    private $id;

    /**
     * @ORM\Column(name="name", type="text", nullable=false)
     */
    private $name;

    /**
     * @ORM\Column(name="type", type="text", nullable=false)
     */
    private $type;

    /**
     * @ORM\Column(name="level", type="integer", nullable=false)
     */
    private $level;

    /**
     * @var Location
     * @ORM\ManyToOne(targetEntity="\App\Entity\Location", inversedBy="regionChildren", cascade={"persist"})
     * @ORM\JoinColumn(name="region_id", referencedColumnName="id")
     */
    private $region;

    /**
     * @var Location
     * @ORM\ManyToOne(targetEntity="\App\Entity\Location", inversedBy="children", cascade={"persist"})
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id")
     */
    private $parent;

    /**
     * @var Location[]|ArrayCollection
     * @ORM\OneToMany(targetEntity="\App\Entity\Location", mappedBy="parent", cascade={"persist"})
     */
    private $children;

    /**
     * @var Location[]|ArrayCollection
     * @ORM\OneToMany(targetEntity="\App\Entity\Location", mappedBy="region", cascade={"persist"})
     */
    private $regionChildren;

    /**
     * @ORM\Column(name="coordinates", type="json_array", nullable=true)
     */
    private $coordinates;

    /**
     * @var \DateTime
     * @ORM\Column(name="created_at", type="datetime", nullable=false)
     */
    private $createdAt;

    /**
     * Location constructor.
     */
    public function __construct()
    {
        $this->children = new ArrayCollection();
        $this->regionChildren = new ArrayCollection();
        $this->createdAt = new \DateTime();
        $this->coordinates = [];
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param string $type
     * @return $this
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return string
     */
    public function getLevel()
    {
        return $this->level;
    }

    /**
     * @param string $level
     * @return $this
     */
    public function setLevel($level)
    {
        $this->level = $level;

        return $this;
    }

    /**
     * @return Location|null
     */
    public function getRegion()
    {
        return $this->region;
    }

    /**
     * @param Location $region
     * @return $this
     */
    public function setRegion($region)
    {
        $this->region = $region;

        return $this;
    }

    /**
     * @return Location|null
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * @param Location $parent
     * @return $this
     */
    public function setParent($parent)
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * @return array|null
     */
    public function getCoordinates()
    {
        return $this->coordinates;
    }

    /**
     * @param array|null $coordinates
     * @return $this
     */
    public function setCoordinates($coordinates)
    {
        $this->coordinates = $coordinates;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @param \DateTime $createdAt
     * @return $this
     */
    public function setCreatedAt(\DateTime $createdAt = null)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * @return Location[]
     */
    public function getParents()
    {
        $parents = [];
        $parent = $this->getParent();

        while ($parent) {
            $parents[] = $parent;
            $parent = $parent->getParent();
        }

        return $parents;
    }

    /**
     * @param int $level
     * @return Location|null
     */
    public function getParentByLevel($level)
    {
        foreach ($this->getParents() as $parent) {
            if ($parent->getLevel() == $level) {
                return $parent;
            }
        }

        return null;
    }

    /**
     * @return Location[]|ArrayCollection
     */
    public function getChildren()
    {
        return $this->children;
    }

    /**
     * @param Location $children
     * @return $this
     */
    public function addChildren($children)
    {
        $this->children->add($children);

        return $this;
    }

    /**
     * @param Location $children
     * @return $this
     */
    public function removeChildren($children)
    {
        $this->children->removeElement($children);

        return $this;
    }

    /**
     * @return Location[]|ArrayCollection
     */
    public function getRegionChildren()
    {
        return $this->regionChildren;
    }

    /**
     * @return Location|null
     */
    public function getAdministrativeArea()
    {
        $administrativeArea = null;

        if ($this->getLevel() === 1) {
            $administrativeArea = $this;
        } elseif ($this->getLevel() >= 2) {
            $administrativeArea = $this->getParentByLevel(1);
        }

        return $administrativeArea;
    }
}
