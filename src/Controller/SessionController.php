<?php
namespace App\Controller;

use App\Entity\Session;
use App\Entity\Subject;
use App\Entity\Teacher;
use App\Entity\Room;
use App\Entity\ClassEntity;
use App\Repository\SessionRepository;
use App\Repository\SubjectRepository;
use App\Repository\TeacherRepository;
use App\Repository\RoomRepository;
use App\Repository\ClassEntityRepository;
use App\Repository\UserRepository;  
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface; 
use Symfony\Component\HttpFoundation\JsonResponse;

class SessionController extends AbstractController
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/session', name: 'app_session', methods: ['GET'])]
    public function index(): Response
    {
        return $this->render('session/index.html.twig', [
            'controller_name' => 'SessionController',
        ]);
    }

    #[Route('/sessions', name: 'create_session', methods: ['POST'])]
    public function create(
        Request $request,
        SessionRepository $sessionRepository,
        SubjectRepository $subjectRepository,
        TeacherRepository $teacherRepository,
        RoomRepository $roomRepository,
        ClassEntityRepository $classEntityRepository
    ): Response {
        $data = json_decode($request->getContent(), true);
        $session = new Session();

        $sessionIds = ["ST001", "ST002", "ST003"];
        $session->setSessionId($sessionIds[array_rand($sessionIds)]);
        if (isset($data['session_date'])) {
            $session->setSessionDate(new \DateTime($data['session_date']));
        } else {
            return $this->json(['error' => 'Session date is required'], 400);
        }

        if (isset($data['start_time'])) {
            $session->setStartTime(new \DateTime($data['start_time']));
        } else {
            return $this->json(['error' => 'Start time is required'], 400);
        }

        if (isset($data['end_time'])) {
            $session->setEndTime(new \DateTime($data['end_time']));
        } else {
            return $this->json(['error' => 'End time is required'], 400);
        }

        if (isset($data['subject_id'])) {
            $subject = $subjectRepository->find($data['subject_id']);
            if (!$subject) {
                return $this->json(['error' => 'Subject not found'], 404);
            }
            $session->setSubject($subject);
        }

        if (isset($data['teacher_id'])) {
            $teacher = $teacherRepository->find($data['teacher_id']);
            if (!$teacher) {
                return $this->json(['error' => 'Teacher not found'], 404);
            }
            $session->setTeacher($teacher);
        }

        if (isset($data['room_id'])) {
            $room = $roomRepository->find($data['room_id']);
            if (!$room) {
                return $this->json(['error' => 'Room not found'], 404);
            }
            $session->setRoom($room);
        }

        if (isset($data['class_id'])) {
            $classEntity = $classEntityRepository->find($data['class_id']);
            if (!$classEntity) {
                return $this->json(['error' => 'Class not found'], 404);
            }
            $session->setClassEntity($classEntity);
        }

        $this->entityManager->persist($session);
        $this->entityManager->flush();

        return $this->json(['message' => 'Session created successfully'], 201);
       
    }

    #[Route('/sessions', name: 'list_sessions', methods: ['GET'])]
    public function list(SessionRepository $sessionRepository): Response
    {
        $sessions = $sessionRepository->findAll();
        $data = [];
        foreach ($sessions as $session) {
            $data[] = [
                'id' => $session->getId(),
                'session_date' => $session->getSessionDate()->format('Y-m-d'),
                'start_time' => $session->getStartTime()->format('H:i:s'),
                'end_time' => $session->getEndTime()->format('H:i:s'),
                'session' => $session->getSessionId(), 
                'subject' => $session->getSubject() ? $session->getSubject()->getSubjectName() : null,
                'teacher' => $session->getTeacher() ? $session->getTeacher()->getFullName() : null,
                'room' => $session->getRoom() ? $session->getRoom()->getRoomName() : null,
                'class' => $session->getClassEntity() ? $session->getClassEntity()->getClassName() : null,
            ];
        }

        return $this->json($data);
    }

    #[Route('/sessions/{id}', name: 'get_session', methods: ['GET'])]
    public function getSession(int $id, SessionRepository $sessionRepository): Response
    {
        $session = $sessionRepository->find($id);

        if (!$session) {
            return $this->json(['error' => 'Session not found'], 404);
        }
        return $this->json([
            'id' => $session->getId(),
            'session_date' => $session->getSessionDate()->format('Y-m-d'),
            'start_time' => $session->getStartTime()->format('H:i:s'),
            'end_time' => $session->getEndTime()->format('H:i:s'),
            'subject' => $session->getSubject() ? $session->getSubject()->getSubjectName() : null,
            'teacher' => $session->getTeacher() ? $session->getTeacher()->getFullName() : null,
            'room' => $session->getRoom() ? $session->getRoom()->getRoomName() : null,
            'class' => $session->getClassEntity() ? $session->getClassEntity()->getClassName() : null,
        ]);
    }

    #[Route('/sessions/{id}', name: 'delete_session', methods: ['DELETE'])]
    public function delete(int $id): Response
    {
        // Utilisation de l'EntityManager pour récupérer la session à supprimer
        $session = $this->entityManager->getRepository(Session::class)->find($id);

        if (!$session) {
            return $this->json(['error' => 'Session not found'], 404);
        }
        $this->entityManager->remove($session);
        $this->entityManager->flush();

        return $this->json(['message' => 'Session deleted successfully']);
    }

    #[Route('/sessions/{id}', name: 'update_session', methods: ['PUT'])]
    public function update(
        int $id,
        Request $request,
        SessionRepository $sessionRepository,
        SubjectRepository $subjectRepository,
        TeacherRepository $teacherRepository,
        RoomRepository $roomRepository,
        ClassEntityRepository $classEntityRepository
    ): Response {
        $session = $sessionRepository->find($id);

        if (!$session) {
            return $this->json(['error' => 'Session not found'], 404);
        }
        $data = json_decode($request->getContent(), true);

        if (isset($data['session_date'])) {
            $session->setSessionDate(new \DateTime($data['session_date']));
        }
        if (isset($data['start_time'])) {
            $session->setStartTime(new \DateTime($data['start_time']));
        }
        if (isset($data['end_time'])) {
            $session->setEndTime(new \DateTime($data['end_time']));
        }

        if (isset($data['subject_id'])) {
            $subject = $subjectRepository->find($data['subject_id']);
            if (!$subject) {
                return $this->json(['error' => 'Subject not found'], 404);
            }
            $session->setSubject($subject);
        }

        if (isset($data['teacher_id'])) {
            $teacher = $teacherRepository->find($data['teacher_id']);
            if (!$teacher) {
                return $this->json(['error' => 'Teacher not found'], 404);
            }
            $session->setTeacher($teacher);
        }

        if (isset($data['room_id'])) {
            $room = $roomRepository->find($data['room_id']);
            if (!$room) {
                return $this->json(['error' => 'Room not found'], 404);
            }
            $session->setRoom($room);
        }

        if (isset($data['class_id'])) {
            $classEntity = $classEntityRepository->find($data['class_id']);
            if (!$classEntity) {
                return $this->json(['error' => 'Class not found'], 404);
            }
            $session->setClassEntity($classEntity);
        }

        $this->entityManager->flush();

        return $this->json(['message' => 'Session updated successfully']);
    }



    #[Route('/api/login', methods: ['POST'])]
    public function login(Request $request, UserRepository $userRepo): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $email = $data['email'] ?? null;
        $password = $data['password'] ?? null;

        if (!$email || !$password) {
            return new JsonResponse(['error' => 'Email et mot de passe requis.'], 400);
        }

        $user = $userRepo->findOneBy(['email' => $email]);

        if (!$user || $user->getPassword() !== $password) {
            return new JsonResponse(['error' => 'Identifiants invalides.'], 401);
        }

        $roles = $user->getRoles();

        return new JsonResponse([
            'message' => 'Connexion réussie.',
            'role' => $roles[0] ?? 'ROLE_USER', 
        ]);
    }
    #[Route('/api/dashboard/stats', name: 'dashboard_stats', methods: ['GET'])]
    public function getDashboardStats(
        SessionRepository $sessionRepo,
        TeacherRepository $teacherRepo,
        RoomRepository $roomRepo,
        UserRepository $userRepo
    ): JsonResponse {
        $totalSessions = $sessionRepo->count([]);
        $totalTeachers = $teacherRepo->count([]);
        $totalRooms = $roomRepo->count([]);
        $totalUsers = $userRepo->count([]);
    
        $stats = [
            'total_sessions' => $totalSessions,
            'total_teachers' => $totalTeachers,
            'total_rooms' => $totalRooms,
            'total_users' => $totalUsers, 
        ];
    
        return $this->json($stats);
    }
}
