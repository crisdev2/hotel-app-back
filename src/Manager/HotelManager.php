<?php
namespace App\Manager;

use App\Entity\Habitacion;
use App\Entity\Hotel;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

class HotelManager 
{
  private $doctrine;
  private $entityManager;

  public function __construct(
    ManagerRegistry $doctrine,
    EntityManagerInterface $entityManager,
  )
  {
    $this->doctrine = $doctrine;
    $this->entityManager = $entityManager;
  }

  public function save($data, $id = null)
  {
    if ($id) {
      /** @var Hotel $record */
      $record = $this->doctrine
        ->getRepository(Hotel::class)
        ->find($id);
    } else {
      $record = new Hotel();
    }
    $record->setNombre($data['nombre']);
    $record->setDireccion($data['direccion']);
    $record->setCiudad($data['ciudad']);
    $record->setNit($data['nit']);
    $record->setHabitaciones($data['habitaciones']);
    $this->entityManager->persist($record);

    if ($id) {
      /** @var Habitacion[] $habitaciones */
      $habitaciones = $this->doctrine
        ->getRepository(Habitacion::class)
        ->findBy(['idHotel' => $id]);
      foreach ($habitaciones AS $row) {
        $this->entityManager->remove($row);
      }
    }

    foreach ($data['idHabitaciones'] as $row) {
      $habitacion = new Habitacion();
      $habitacion->setCantidad($row['cantidad']);
      $habitacion->setTipo($row['tipo']);
      $habitacion->setAcomodacion($row['acomodacion']);
      $habitacion->setIdHotel($record);
      $this->entityManager->persist($habitacion);
    }
    
    $this->entityManager->flush();

    return $record;
  }
}