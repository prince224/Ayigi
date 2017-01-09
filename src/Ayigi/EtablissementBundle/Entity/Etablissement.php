<?php

namespace Ayigi\EtablissementBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Etablissement
 *
 * @ORM\Table(name="etablissement")
 * @ORM\Entity(repositoryClass="Ayigi\EtablissementBundle\Repository\EtablissementRepository")
 */
class Etablissement
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
    * @ORM\ManyToMany(targetEntity="Ayigi\PlateFormeBundle\Entity\PaysDestination", cascade={"persist"})
    */
    private $paysDestination;

    /**
     * @var string
     *
     * @ORM\Column(name="nom", type="string", length=255)
     */
    private $nom;

    /**
     * @var string
     *
     * @ORM\Column(name="adresse", type="string", length=255)
     */
    private $adresse;

    /**
     * @var string
     *
     * @ORM\Column(name="ville", type="string", length=255)
     */
    private $ville;

    /**
     * @var string
     *
     * @ORM\Column(name="codepostal", type="string", length=255)
     */
    private $codepostal;

    /**
     * @var string
     *
     * @ORM\Column(name="telephone", type="string", length=255)
     */
    private $telephone;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255)
     */
    private $email;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="datecreation", type="date")
     */
    private $datecreation;

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set nom
     *
     * @param string $nom
     *
     * @return Etablissement
     */
    public function setNom($nom)
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * Get nom
     *
     * @return string
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * Set adresse
     *
     * @param string $adresse
     *
     * @return Etablissement
     */
    public function setAdresse($adresse)
    {
        $this->adresse = $adresse;

        return $this;
    }

    /**
     * Get adresse
     *
     * @return string
     */
    public function getAdresse()
    {
        return $this->adresse;
    }

    /**
     * Set ville
     *
     * @param string $ville
     *
     * @return Etablissement
     */
    public function setVille($ville)
    {
        $this->ville = $ville;

        return $this;
    }

    /**
     * Get ville
     *
     * @return string
     */
    public function getVille()
    {
        return $this->ville;
    }

    /**
     * Set codepostal
     *
     * @param string $codepostal
     *
     * @return Etablissement
     */
    public function setCodepostal($codepostal)
    {
        $this->codepostal = $codepostal;

        return $this;
    }

    /**
     * Get codepostal
     *
     * @return string
     */
    public function getCodepostal()
    {
        return $this->codepostal;
    }

    /**
     * Set telephone
     *
     * @param string $telephone
     *
     * @return Etablissement
     */
    public function setTelephone($telephone)
    {
        $this->telephone = $telephone;

        return $this;
    }

    /**
     * Get telephone
     *
     * @return string
     */
    public function getTelephone()
    {
        return $this->telephone;
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return Etablissement
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }
  
   
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->paysDestination = new \Doctrine\Common\Collections\ArrayCollection();
        $this->datecreation = new \DateTime();
    }

    /**
     * Set datecreation
     *
     * @param \DateTime $datecreation
     *
     * @return Etablissement
     */
    public function setdatecreation($datecreation)
    {
        $this->datecreation = $datecreation;

        return $this;
    }

    /**
     * Get datecreation
     *
     * @return \DateTime
     */
    public function getdatecreation()
    {
        return $this->datecreation;
    }

    /**
     * Add paysDestination
     *
     * @param \Ayigi\PlateFormeBundle\Entity\PaysDestination $paysDestination
     *
     * @return Etablissement
     */
    public function addPaysDestination(\Ayigi\PlateFormeBundle\Entity\PaysDestination $paysDestination)
    {
        $this->paysDestination[] = $paysDestination;

        return $this;
    }

    /**
     * Remove paysDestination
     *
     * @param \Ayigi\PlateFormeBundle\Entity\PaysDestination $paysDestination
     */
    public function removePaysDestination(\Ayigi\PlateFormeBundle\Entity\PaysDestination $paysDestination)
    {
        $this->paysDestination->removeElement($paysDestination);
    }

    /**
     * Get paysDestination
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPaysDestination()
    {
        return $this->paysDestination;
    }
}
