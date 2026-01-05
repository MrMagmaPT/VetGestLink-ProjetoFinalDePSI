<?php

namespace backend\tests\functional\_support;

use Yii;

/**
 * Trait para inicializar RBAC em testes funcionais
 */
trait RbacHelper
{
    private static $rbacInitialized = false;

    protected function initializeRbac()
    {
        if (self::$rbacInitialized) {
            return;
        }

        $auth = Yii::$app->authManager;
        
        // Limpa tudo antes de iniciar
        $auth->removeAll();

        // Cria permissão backendAccess
        $backendAccess = $auth->createPermission('backendAccess');
        $backendAccess->description = 'Acesso ao backend';
        $auth->add($backendAccess);

        // Cria permissão viewAnimals
        $viewAnimals = $auth->createPermission('viewAnimals');
        $viewAnimals->description = 'Visualizar animais';
        $auth->add($viewAnimals);

        // Cria permissão viewInvoices
        $viewInvoices = $auth->createPermission('viewInvoices');
        $viewInvoices->description = 'Visualizar faturas';
        $auth->add($viewInvoices);

        // Cria permissão viewAppointments
        $viewAppointments = $auth->createPermission('viewAppointments');
        $viewAppointments->description = 'Visualizar marcações';
        $auth->add($viewAppointments);

        // Cria permissão viewConsultations
        $viewConsultations = $auth->createPermission('viewConsultations');
        $viewConsultations->description = 'Visualizar consultas';
        $auth->add($viewConsultations);

        // Cria papel admin
        $admin = $auth->createRole('admin');
        $admin->description = 'Administrador do sistema';
        $auth->add($admin);
        $auth->addChild($admin, $backendAccess);
        $auth->addChild($admin, $viewAnimals);
        $auth->addChild($admin, $viewInvoices);
        $auth->addChild($admin, $viewAppointments);
        $auth->addChild($admin, $viewConsultations);

        // Cria papel veterinario
        $veterinario = $auth->createRole('veterinario');
        $veterinario->description = 'Veterinário';
        $auth->add($veterinario);
        $auth->addChild($veterinario, $backendAccess);
        $auth->addChild($veterinario, $viewAnimals);
        $auth->addChild($veterinario, $viewAppointments);
        $auth->addChild($veterinario, $viewConsultations);
        // Veterinário NÃO tem acesso a faturas

        // Cria papel rececionista
        $rececionista = $auth->createRole('rececionista');
        $rececionista->description = 'Rececionista';
        $auth->add($rececionista);
        $auth->addChild($rececionista, $backendAccess);
        $auth->addChild($rececionista, $viewAnimals);
        $auth->addChild($rececionista, $viewInvoices);
        $auth->addChild($rececionista, $viewAppointments);
        $auth->addChild($rececionista, $viewConsultations);

        // Cria papel cliente
        $cliente = $auth->createRole('cliente');
        $cliente->description = 'Cliente';
        $auth->add($cliente);
        // Cliente não tem backendAccess

        self::$rbacInitialized = true;
    }
}
