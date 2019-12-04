<?php

namespace App\Controller;

use App\Entity\Item;
use App\Repository\ItemRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function home(Request $request, ItemRepository $itemRepository)
    {
        $category = (string)$request->get('category');
        $minPrice = (int)$request->get('minPrice');
        $maxPrice = (int)$request->get('maxPrice');

        $items = $itemRepository->findByFilters($category, $minPrice, $maxPrice);
        $categories = $itemRepository->getDistinctCategories();

        return $this->render('items.html.twig', ['items' => $items, 'categories' => $categories]);
    }

    /**
     * @Route("/buy_item/{id}", name="app_buy")
     */
    public function buyItem(int $id)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $item = $entityManager->getRepository(Item::class)->find($id);

        if (!$item) {
            $this->redirect('home');
        }

        $item->setCount($item->getCount() - 1);
        $entityManager->flush();
        return $this->render('buy.html.twig', ['item' => $item]);
    }

    /**
     * @Route("/vote_item/{id}", name="app_votePos")
     */
    public function votePositive(int $id)
    {

        $entityManager = $this->getDoctrine()->getManager();
        $item = $entityManager->getRepository(Item::class)->find($id);

        if (!$item) {
            $this->redirect('/');
        }

        $item->setPosVotes($item->getPosVotes() + 1);
        $entityManager->flush();

        return $this->redirect('/');
    }

    /**
     * @Route("/down_item/{id}", name="app_voteNeg")
     */
    public function voteNegative(int $id)
    {

        $entityManager = $this->getDoctrine()->getManager();
        $item = $entityManager->getRepository(Item::class)->find($id);

        if (!$item) {
            $this->redirect('/');
        }

        $item->setNegVotes($item->getNegVotes() + 1);
        $entityManager->flush();

        return $this->redirect('/');
    }
}