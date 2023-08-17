<?php

namespace App\Controller;


use App\Entity\User;
use App\Entity\Usuarios;
use App\Service\StringDecod;
use App\Service\VtexGet;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\UX\Chartjs\Builder\ChartBuilderInterface;
use Symfony\UX\Chartjs\Model\Chart;

class HomeController extends AbstractController
{
    #[Route('/')]
    public function index():Response
    {
        return $this->render('home/homepage.html.twig', [
            'login' => ' '
        ]);
    }

    #[Route('/dashboard', name: 'my_dashboard')]
    public function dashboard(Request $request, EntityManagerInterface $entityManager, VtexGet $vtexGet, StringDecod $decod, ChartBuilderInterface $chartBuilder):Response
    {
        session_start();
        $email= $request->query->get('email');
        $senha = $request->query->get('senha');

        $_SESSION['email'] = $email;
        $_SESSION['senha'] = $senha;

        $dadosElo = $entityManager->getRepository(User::class);
        $elos = $dadosElo->findBy([], ['meuSaldo' => 'DESC']);

        $userRepository = $entityManager->getRepository(User::class);
        $user = $userRepository->findByCupom($email);
        $cupomSerach = $decod->explodAspasCupomDb($user);
        $dadosVenda = $vtexGet->getCupom($cupomSerach);

        $dadosVendaValor = $decod->explodBarValue($dadosVenda);
        $dadosVendaQuantidade = $decod->explodBarAmount($dadosVenda);
        $dadosVendaInt = $decod->explodBarValueInt($dadosVenda);

        $userRepository->upSaldo($email, $dadosVendaInt);

        $user = $userRepository->findBy(['email' => $email, 'senha' => $senha]);

        $chart = $chartBuilder->createChart(Chart::TYPE_DOUGHNUT);
        $chart->setData([
           'labels' => ['Vendas'],
           'datasets' => [
               [
               'backgroundColor' => 'rgb(255, 99, 132)',
               'borderColor' => 'rgb(255, 99, 132)',
               'data' => [20],
               ],

           ],
        ]);

        return $this->render('home/dashboard.html.twig', [
                'dados' => $user,
                'dadosVenda' => $dadosVendaValor,
                'dadosQuantidade' => $dadosVendaQuantidade,
                'dadosVendaInt' => $dadosVendaInt,
                'elos' => $elos,
                'chart' => $chart
        ]);
    }

    #[Route('/cadastro')]
    public function cadastro():Response
    {
        return $this->render('home/newUser.html.twig', [
            'cadastro' => ' '
        ]);
    }
    #[Route('/newUser', name:'new_user', methods: ['POST'])]
    public function newUser(Request $request, EntityManagerInterface $entityManager):Response
    {
        $nome = $request->request->get('nome');
        $email= $request->request->get('email');
        $senha = $request->request->get('senha');
        $cupom = $request->request->get('cupom');
        $cpf = $request->request->get('cpf');
        $pix = $request->request->get('pix');
        $roles = ['ROLE_USER'];

        $userRepository = $entityManager->getRepository(User::class);
        $user = $userRepository->findOneBy(['email' => $email]);

        if (!$user){
            $user = $userRepository->findOneBy(['cupom' => $cupom]);
            if ($user){

                $user->setNome($nome);
                $user->setEmail($email);
                $user->setSenha($senha);
                $user->setCpf($cpf);
                $user->setPix($pix);
                $user->setRoles($roles);
                $user->setElo('bronze');
                $user->setMeuSaldo('100');

                $entityManager->flush();

                return $this->render('home/homepage.html.twig',[
                    'cadastro' => 'SUCESSO'
                ]);
            } else {
                return $this->render('home/newUser.html.twig',[
                    'cadastro' => 'SEM_CUPOM'
                ]);
            }
        } else {
            return $this->render('home/newUser.html.twig',[
                'cadastro' => 'JA_CADASTRADO'
            ]);
        }
    }

    public function relatorioComoleto():Response
    {
        return $this->render('home/relatorioCompleto.html.twig');
    }
    #[Route('/meuPerfil', name: 'meu_perfil')]
    public function meuperfil(EntityManagerInterface $entityManager):Response
    {
        session_start();

        $userRepository = $entityManager->getRepository(User::class);
        $user = $userRepository->findBy(['email' => $_SESSION['email'], 'senha' => $_SESSION['senha']]);

        return $this->render('home/meuPerfil.html.twig', [
            'dadosUsuarios' => $user
        ]);
    }
}