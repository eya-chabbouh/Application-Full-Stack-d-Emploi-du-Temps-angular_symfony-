<?php
// src/Command/ImportTimetableCommand.php
namespace App\Command;

use App\Entity\Room;
use App\Entity\Teacher;
use App\Entity\Subject;
use App\Entity\Session;
use App\Entity\Student;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ImportTimetableCommand extends Command
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        parent::__construct();
        $this->em = $em;
    }

    protected function configure()
    {
        $this->setName('app:import-timetable')
            ->setDescription('Import timetable data from JSON file.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int // Ajout de la déclaration du type int ici
    {
        // Utilisation du chemin absolu pour le fichier JSON
        $jsonFilePath = __DIR__ . '/../../public/data/timetable.json';

        // Vérification si le fichier existe
        if (!file_exists($jsonFilePath)) {
            $output->writeln("Erreur : Le fichier JSON n'a pas été trouvé à l'emplacement : $jsonFilePath");
            return Command::FAILURE; // Retourner un code d'erreur
        }

        // Lire le contenu du fichier JSON
        $json = file_get_contents($jsonFilePath);
        $data = json_decode($json, true);

        // Import des données des rooms
        foreach ($data['rooms'] as $roomData) {
            $room = new Room();
            $room->setRoomId($roomData['room_id'])
                 ->setRoomName($roomData['room_name'])
                 ->setCapacity($roomData['capacity'])
                 ->setBuilding($roomData['building'])
                 ->setFloor($roomData['floor']);
            $this->em->persist($room);
        }

        // Import des données des teachers
        foreach ($data['teachers'] as $teacherData) {
            $teacher = new Teacher();
            $teacher->setTeacherId($teacherData['teacher_id'])
                    ->setFirstName($teacherData['first_name'])
                    ->setLastName($teacherData['last_name'])
                    ->setEmail($teacherData['email'])
                    ->setDepartment($teacherData['department'])
                    ->setPhone($teacherData['phone']);
            $this->em->persist($teacher);
        }

        // Import des données des subjects
        foreach ($data['subjects'] as $subjectData) {
            $subject = new Subject();
            $subject->setSubjectId($subjectData['subject_id'])
                    ->setSubjectName($subjectData['subject_name'])
                    ->setSubjectCode($subjectData['subject_code'])
                    ->setDepartment($subjectData['department'])
                    ->setDescription($subjectData['description']);
            $this->em->persist($subject);
        }

        // Import des données des students
        foreach ($data['students'] as $studentData) {
            $student = new Student();
            $student->setStudentId($studentData['student_id'])
                    ->setFirstName($studentData['first_name'])
                    ->setLastName($studentData['last_name'])
                    ->setEmail($studentData['email']);
            $this->em->persist($student);
        }

        // Import des données des sessions
        foreach ($data['sessions'] as $sessionData) {
            $session = new Session();
            $session->setSessionId($sessionData['session_id'])
                    ->setSubjectId($sessionData['subject_id'])
                    ->setTeacherId($sessionData['teacher_id'])
                    ->setRoomId($sessionData['room_id'])
                    ->setClassId($sessionData['class_id'])
                    ->setSessionDate($sessionData['session_date'])
                    ->setStartTime($sessionData['start_time'])
                    ->setEndTime($sessionData['end_time']);
            $this->em->persist($session);
        }

        // Sauvegarder toutes les entités en base de données
        $this->em->flush();

        $output->writeln('Les données du planning ont été importées avec succès!');
        return Command::SUCCESS; // Retourner un code de succès
    }
}
