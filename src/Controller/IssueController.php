<?php

namespace App\Controller;

use App\DTO\IssueDto;
use App\Entity\TimeEntry;
use App\Form\TimeEntryType;
use App\Services\Redmine;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

/**
 * @Route("/issues")
 */
class IssueController extends AbstractController
{
    /**
     * @Route("/{id}/time-entries/new", name="issue_time_entry_new")
     *
     * @param int                   $id
     * @param Request               $request
     * @param Redmine               $redmine
     * @param DenormalizerInterface $serializer
     *
     * @return Response
     */
    public function timeEntryNew(int $id, Request $request, Redmine $redmine, DenormalizerInterface $serializer): Response
    {
        if (!$issueData = $redmine->getIssue($id)) {
            throw $this->createNotFoundException();
        }

        $issue = $serializer->denormalize($issueData, IssueDto::class);

        $timeEntry = new TimeEntry();

        $form = $this->createForm(TimeEntryType::class, $timeEntry);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $redmine->newTimeEntryPerIssue($issue->getId(), $timeEntry->getTime());

            return $this->redirectToRoute('homepage');
        }

        return $this->render('issue/time_entry_new.html.twig', [
            'issue' => $issue,
            'form' => $form->createView(),
        ]);
    }
}
