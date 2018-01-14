<?php

declare(strict_types=1);

namespace Participant\Entity;

use Ramsey\Uuid\Uuid;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class Participant
 *
 * Attention : Doctrine génère des classes proxy qui étendent les entités, celles-ci ne peuvent donc pas être finales !
 *
 * @package Participant\Entity
 * @ORM\Entity(repositoryClass="\Participant\Repository\ParticipantRepository")
 * @ORM\Table(name="participant")
 */
class Participant
{
    /**
     * @ORM\Id
     * @ORM\Column(type="string", length=36)
     **/
    private $id;

    /**
     * @ORM\Column(type="integer", nullable=false)
     */
    private $nb;

    public function __construct(int $nb)
    {
        $this->id = Uuid::uuid4()->toString();
        $this->nb = $nb;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getNb()
    {
        return $this->nb;
    }

    /**
     * @param mixed $nb
     */
    public function setNb($nb)
    {
        $this->nb = $nb;
    }
}