<?php

namespace App\Controller;

use App\DTO\IssueDto;
use App\DTO\TimeEntryDto;
use App\Form\TimeEntryType;
use App\Services\Redmine;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Translation\TranslatorInterface;

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
     * @param TranslatorInterface   $translator
     *
     * @return Response
     */
    public function timeEntryNew(
        int $id,
        Request $request,
        Redmine $redmine,
        DenormalizerInterface $serializer,
        TranslatorInterface $translator
    ): Response {
        if (!$issueData = $redmine->getIssue($id)) {
            throw $this->createNotFoundException();
        }

        $issue = $serializer->denormalize($issueData, IssueDto::class);

        $timeEntry = new TimeEntryDto();

        $form = $this->createForm(TimeEntryType::class, $timeEntry);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($redmine->newTimeEntryPerIssue($issue->getId(), $timeEntry->getTime())) {
                $this->addFlash('success', $translator->trans('time_entry.new_success'));

                return $this->redirectToRoute('homepage');
            }

            $this->addFlash('error', $translator->trans('time_entry.new_error'));
        }

        return $this->render('issue/time_entry_new.html.twig', [
            'issue' => $issue,
            'form' => $form->createView(),
        ]);
    }
}
