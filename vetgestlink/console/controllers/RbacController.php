<?php

namespace console\controllers;

use Yii;
use yii\console\Controller;
class RbacController extends Controller
{
    public function actionInit()
    {
        $auth = Yii::$app->authManager;
        $auth->removeAll();

        //========================================================================
        //Permissisões:

        //Gerir Utilizadores(Veterinarios e Rececionistas,Clientes) (ADMIN)1
        $manageUsers = $auth->createPermission('manageUsers');
        $manageUsers->description = 'Gerir Utilizadores';
        $auth->add($manageUsers);

        //Gerir Produtos (ADMIN)
        $manageProducts = $auth->createPermission('manageProducts');
        $manageProducts->description = 'Gerir Produtos';
        $auth->add($manageProducts);

        //Gerir Inventario (ADMIN)
        $manageInventory = $auth->createPermission('manageInventory');
        $manageInventory->description = 'Gerir Inventario';
        $auth->add($manageInventory);

        //Gerir Consultas (VETERINARIO)
        $manageConsultations = $auth->createPermission('manageConsultations');
        $manageConsultations->description = 'Gerir Consultas';
        $auth->add($manageConsultations);

        //Gerir Animais (VETERINARIO)
        $manageAnimals = $auth->createPermission('manageAnimals');
        $manageAnimals->description = 'Gerir Animais';
        $auth->add($manageAnimals);

        //Atribuir medicação (VETERINARIO)
        $assignMedication = $auth->createPermission('assignMedication');
        $assignMedication->description = 'Atribuir medicação';
        $auth->add($assignMedication);

        //Gerir Marcações (RECECIONISTA)
        $manageAppointments = $auth->createPermission('manageAppointments');
        $manageAppointments->description = 'Gerir Marcações';
        $auth->add($manageAppointments);

        //Gerir Clientes (RECECIONISTA)
        $manageClients = $auth->createPermission('manageClients');
        $manageClients->description = 'Gerir Clientes';
        $auth->add($manageClients);

        //Fazer Login (CONVIDADO)
        $login = $auth->createPermission('login');
        $login->description = 'Fazer login';
        $auth->add($login);

        //Fazer Registo no sistema (CONVIDADO)
        $register = $auth->createPermission('register');
        $register->description = 'Registar-se';
        $auth->add($register);

        //Ver Home Page (CONVIDADO)
        $viewHome = $auth->createPermission('viewHome');
        $viewHome->description = 'Visualizar página inicial';
        $auth->add($viewHome);

        //Editar notas do dono do animal (Cliente)
        $editOwnerNotes = $auth->createPermission('editOwnerNotes');
        $editOwnerNotes->description = 'Editar notas dono do seu animal';
        $auth->add($editOwnerNotes);

        //Visualizar notas do dono (Cliente)
        $viewAnimalInfo = $auth->createPermission('viewAnimalInfo');
        $viewAnimalInfo->description = 'Visualizar informações do seu animal';
        $auth->add($viewAnimalInfo);

        //Editar perfil próprio(Cliente)
        $editOwnInfo = $auth->createPermission('editOwnInfo');
        $editOwnInfo->description = 'Editar as suas informações';
        $auth->add($editOwnInfo);

        //Visualizar histórico de consultas (Cliente)
        $viewConsultationHistory = $auth->createPermission('viewConsultationHistory');
        $viewConsultationHistory->description = 'Visualizar histórico de consultas';
        $auth->add($viewConsultationHistory);

        //Visualizar faturas (Cliente)
        $viewInvoices = $auth->createPermission('viewInvoices');
        $viewInvoices->description = 'Visualizar faturas';
        $auth->add($viewInvoices);
        //========================================================================
        //Roles:

        //administrador
        $admin = $auth->createRole('admin');
        $auth->add($admin);

        $auth->addChild($admin, $manageUsers);
        $auth->addChild($admin, $manageProducts);
        $auth->addChild($admin, $manageInventory);
        //===============================================
        //veterinario
        $veterinario = $auth->createRole('veterinario');
        $auth->add($veterinario);

        $auth->addChild($veterinario, $manageConsultations);
        $auth->addChild($veterinario, $manageAnimals);
        $auth->addChild($veterinario, $assignMedication);

        //===============================================
        //rececionista
        $recepcionista = $auth->createRole('recepcionista');
        $auth->add($recepcionista);

        $auth->addChild($recepcionista, $manageConsultations);
        $auth->addChild($recepcionista, $manageAppointments);
        $auth->addChild($recepcionista, $manageClients);

        //===============================================
        //cliente
        $cliente = $auth->createRole('cliente');
        $auth->add($cliente);

        $auth->addChild($cliente, $editOwnerNotes);
        $auth->addChild($cliente, $viewAnimalInfo);
        $auth->addChild($cliente, $editOwnInfo);
        $auth->addChild($cliente, $viewConsultationHistory);
        $auth->addChild($cliente, $viewInvoices);

        //===============================================
        //convidado
        $convidado = $auth->createRole('convidado');
        $auth->add($convidado);

        $auth->addChild($convidado, $viewHome);
        $auth->addChild($convidado, $login);
        $auth->addChild($convidado, $register);

        // Herança de Roles
        $auth->addChild($admin, $veterinario);
        $auth->addChild($admin, $recepcionista);
        $auth->addChild($admin, $cliente);

        $auth->addChild($veterinario, $cliente);
        $auth->addChild($recepcionista, $cliente);

        $auth->addChild($cliente, $convidado);

        //Mensagem pra dar feedback que rodou o script
        echo "✅ RBAC inicializado com sucesso ✅\n";
    }



}