<?php

namespace AppBundle\Controller;

use eZ\Bundle\EzPublishCoreBundle\Controller;
use eZ\Publish\Core\MVC\Symfony\View\ContentView;

class RideController extends Controller
{
    /**
     * Action used to display a ride
     *    - Adds the list of all related Landmarks to the response.
     *
     * @param ContentView $view
     *
     * @return ContentView $view
     */
    public function viewRideWithLandmarksAction(ContentView $view)
    {
        $repository = $this->getRepository();
        $contentService = $repository->getContentService();
        $currentContent = $view->getContent();
        $landmarksListId = $currentContent->getFieldValue('landmarks');
        $landmarksList = [];

        foreach ($landmarksListId->destinationContentIds as $landmarkId) {
            $landmarksList[$landmarkId] = $contentService->loadContent($landmarkId);
        }

        $view->addParameters(['landmarksList' => $landmarksList]);

        return $view;
    }
}
