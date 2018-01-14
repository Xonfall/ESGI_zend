<?php

namespace Meetup\Controller;

use Meetup\Form\MeetupForm;
use Meetup\Repository\MeetupRepository;
use Zend\Http\PhpEnvironment\Request;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class IndexController extends AbstractActionController
{
    /**
     * @var MeetupRepository
     */
    private $meetupRepository;

    /**
     * @var MeetupForm
     */
    private $meetupForm;

    public function __construct(MeetupRepository $meetupRepository, MeetupForm $meetupForm)
    {
        $this->meetupRepository = $meetupRepository;
        $this->meetupForm = $meetupForm;
    }

    public function indexAction()
    {
        return new ViewModel([
            'meetups' => $this->meetupRepository->findAll(),
        ]);
    }

    public function addAction()
    {
        $form = $this->meetupForm;

        /* @var $request Request */
        $request = $this->getRequest();

        if ($request->isPost())
        {
            $form->setData($request->getPost());
            if ($form->isValid() && $form->checkDates($form->getData()['debut'], $form->getData()))
            {
                $meetup = $this->meetupRepository->createMeetup(
                    $form->getData()['title'],
                    $form->getData()['description'],
                    $form->getData()['debut'],
                    $form->getData()['fin'] ?? ''
                );
                $this->meetupRepository->add($meetup);
                return $this->redirect()->toRoute('meetup_home');
            }
        }

        $form->prepare();

        return new ViewModel([
            'form' => $form,
        ]);
    }

    public function editAction()
    {
        $form = $this->meetupForm;

        $id = $this->params()->fromRoute('id');
        $meetup = $this->meetupRepository->findOneBy(['id' => $id]);

        if ($meetup === null)
        {
            return $this->redirect()->toRoute('meetup_home');
        }

        /* @var $request Request */
        $request = $this->getRequest();

        if ($request->isPost()) {
            $data = $this->params()->fromPost();
            $form->setData($data);
            if ($form->isValid())
            {
                $data = $form->getData();

                $meetup->setTitle($data['title']);
                $meetup->setDescription($data['description']);
                $meetup->setStartAt($data['debut']);
                $meetup->setEndAt($data['fin']);

                $this->meetupRepository->updateMeetup($data);

                return $this->redirect()->toRoute('meetup_home');
            }
        } else {
                $data = [
                    'title' => $meetup->getTitle(),
                    'description' => $meetup->getDescription(),
                    'debut' => $meetup->getStartAt(),
                    'fin' => $meetup->getendAt(),
                ];

                $form->setData($data);
        }

        //$form->prepare();

        return new ViewModel([
            'form' => $form,
        ]);
    }

    public function showAction()
    {
        $id = $this->params()->fromRoute('id');
        $meetup = $this->meetupRepository->findOneBy(['id' => $id]);

        if ($meetup === null)
        {
            return $this->redirect()->toRoute('meetup_home');
        }

        return new ViewModel([
            'meetup' => $meetup,
        ]);
    }

    public function deleteAction()
    {
        $id = $this->params()->fromRoute('id');
        $meetup = $this->meetupRepository->findOneBy(['id' => $id]);

        if ($meetup === null)
        {
            return $this->redirect()->toRoute('meetup_home');
        }

        try
        {
            $this->meetupRepository->remove($meetup);

        } catch (\Exception $exception)
        {
            return $this->redirect()->toRoute('meetup_home');

        }
        return $this->redirect()->toRoute('meetup_home');
    }
}