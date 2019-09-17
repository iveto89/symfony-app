<?php

namespace App\Controller;

use App\Service\Greeting;
use App\Service\VeryBadDesign;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\RouterInterface;

/**
 * Class BlogController
 * @package App\Controller
 * @Route("/blog")
 */
class BlogController extends AbstractController
{
    /**
     * @var Greeting
     */
    private $greeting;

    /**
     * @var VeryBadDesign
     */
    private $badDesign;
    /**
     * @var SessionInterface
     */
    private $session;
    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * BlogController constructor.
     * @param Greeting $greeting
     */
    public function __construct(
        Greeting $greeting,
        VeryBadDesign $badDesign,
        SessionInterface $session,
        RouterInterface $router
    )
    {
        $this->greeting = $greeting;
        $this->badDesign = $badDesign;
        $this->session = $session;
        $this->router = $router;
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/", name="blog_index")
     */
    public function index()
    {
        return $this->render('blog/index.html.twig', [
            'posts' => $this->session->get('posts')
        ]);
    }

    /**
     * @Route("/add", name="blog_add")
     */
    public function add()
    {
        $posts = $this->session->get('posts');
        $posts[uniqid()] = [
            'title' => 'A random title '.rand(1, 500),
            'text' => 'Some random text '.rand(1, 500),
            'date' => new \DateTime()
        ];
        $this->session->set('posts', $posts);

        return new RedirectResponse($this->router->generate('blog_index'));
    }

    /**
     * @param $id
     * @Route("/show/{id}", name="blog_show")
     */
    public function show($id)
    {
        $posts = $this->session->get('posts');

        if (!$posts || !isset($posts[$id])) {
            throw new NotFoundHttpException('Post not found');
        }

        return $this->render('blog/post.html.twig', [
            'id' => $id,
            'post' => $posts[$id]
        ]);
    }
}
