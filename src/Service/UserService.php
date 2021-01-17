<?php

namespace App\Service;

use App\Entity\Customer;
use App\Entity\User;
use App\Repository\UserRepository;
use App\Util\DateTime;
use FOS\UserBundle\Model\UserManagerInterface;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;

class UserService
{
    const JOB_ACTIVE = true;

    /**
     * @var UserRepository
     */
    private $userRepository;
    /**
     * @var EncoderFactoryInterface
     */
    protected $encoderFactory;

    private $userManager;

    /**
     * CustomerService constructor.
     */
    public function __construct(EncoderFactoryInterface $encoderFactory, UserManagerInterface $userManager, UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
        $this->encoderFactory = $encoderFactory;
        $this->userManager = $userManager;
    }

    /**
     * Creates a new Job
     *  - All new jobs will created as active
     *  - createdAt and updatedAt will be the current UTC datetime.
     *
     * @throws \Doctrine\ORM\ORMException
     */
    public function create(User $user): void
    {
        $now = DateTime::getDateTime();
        $dateBorn = DateTime::getDateTime($user->getDateborn());
        $user->setCreatedAt($now);
        $user->setUpdatedAt($now);
        $user->setDateborn($dateBorn);

        // $customer->setIsActive(static::JOB_ACTIVE);

        $this->userRepository->create($user);
    }

    /**
     * @return User[]
     */
    public function findAll()
    {
        return $this->userRepository->findAll();
    }

    /**
     * @param $id
     *
     * @return User|null
     */
    public function find($id)
    {
        return $this->userRepository->find($id);
    }

    /**
     * @param $username
     *
     * @return User|null
     */
    public function findByName($username)
    {
        return $this->userRepository->loadUserByUsername($username);
    }

    /**
     * Creates a new Job.
     *
     * @throws \Doctrine\ORM\ORMException
     */
    public function changePassword($username, $oldpassworld, $newpassworld): ?User
    {
        $user = $this->userRepository->findOneBy(['username' => $username]);
        if (!$user) {
            return null;
        }
        $encoder = $this->encoderFactory->getEncoder($user);
        $isValid = $encoder->isPasswordValid($user->getPassword(), $oldpassworld, $user->getSalt());
        if (!$isValid) {
            return null;
        }
        $user->setPlainPassword($newpassworld);
        $this->userManager->updateUser($user);

        return $user;
    }

    /**
     * Creates a new Job.
     *
     * @throws \Doctrine\ORM\ORMException
     */
    public function login($username, $passworld): ?User
    {
        $user = $this->userRepository->findOneBy(['username' => $username]);

        $encoder = $this->encoderFactory->getEncoder($user);
        //$isValid = $this->encoder->isPasswordValid($user, $pass);
        $isValid = $encoder->isPasswordValid($user->getPassword(), $passworld, $user->getSalt());
        if (false == $isValid) {
            return null;
        }
        $token = base64_encode($username);

        return $user;
    }
}
