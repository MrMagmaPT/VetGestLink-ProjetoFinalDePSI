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

        //GERIR UTILIZADORES (ADMIN)

        // CREATE - Criar utilizadores (ADMIN)
        $createUser = $auth->createPermission('createUser');
        $createUser->description = 'Criar utilizadores';
        $auth->add($createUser);

        // READ - Visualizar utilizadores (ADMIN)
        $viewUsers = $auth->createPermission('viewUsers');
        $viewUsers->description = 'Visualizar utilizadores';
        $auth->add($viewUsers);

        // UPDATE - Atualizar utilizadores (ADMIN)
        $updateUser = $auth->createPermission('updateUser');
        $updateUser->description = 'Atualizar utilizadores';
        $auth->add($updateUser);

        // DELETE - Eliminar utilizadores (ADMIN)
        $deleteUser = $auth->createPermission('deleteUser');
        $deleteUser->description = 'Eliminar utilizadores';
        $auth->add($deleteUser);

        //--------------------------------------------------------
        //??????????????????????????????????????????????????????????????????????????
        //GERIR PRODUTOS (ADMIN)

        // CREATE - Criar produtos (ADMIN)
        $createProduct = $auth->createPermission('createProduct');
        $createProduct->description = 'Criar produtos';
        $auth->add($createProduct);

        // READ - Visualizar produtos (ADMIN)
        $viewProducts = $auth->createPermission('viewProducts');
        $viewProducts->description = 'Visualizar produtos';
        $auth->add($viewProducts);

        // UPDATE - Atualizar produtos (ADMIN)
        $updateProduct = $auth->createPermission('updateProduct');
        $updateProduct->description = 'Atualizar produtos';
        $auth->add($updateProduct);

        // DELETE - Eliminar produtos (ADMIN)
        $deleteProduct = $auth->createPermission('deleteProduct');
        $deleteProduct->description = 'Eliminar produtos';
        $auth->add($deleteProduct);

        //--------------------------------------------------------
        //GERIR INVENTÁRIO (ADMIN)

        // CREATE - Criar entradas de inventário (ADMIN)
        $createInventory = $auth->createPermission('createInventory');
        $createInventory->description = 'Criar entradas de inventário';
        $auth->add($createInventory);

        // READ - Visualizar inventário (ADMIN)
        $viewInventory = $auth->createPermission('viewInventory');
        $viewInventory->description = 'Visualizar inventário';
        $auth->add($viewInventory);

        // UPDATE - Atualizar inventário (ADMIN)
        $updateInventory = $auth->createPermission('updateInventory');
        $updateInventory->description = 'Atualizar inventário';
        $auth->add($updateInventory);

        // DELETE - Eliminar entradas de inventário (ADMIN)
        $deleteInventory = $auth->createPermission('deleteInventory');
        $deleteInventory->description = 'Eliminar entradas de inventário';
        $auth->add($deleteInventory);

        //????????????????????????????????????????????????????????????????????????????????
        //----------------------------------------------------------------
        //GERIR CONSULTAS (VETERINÁRIO)

        // CREATE - Criar consultas (VETERINÁRIO)
        $createConsultation = $auth->createPermission('createConsultation');
        $createConsultation->description = 'Criar consultas';
        $auth->add($createConsultation);

        // READ - Visualizar consultas (VETERINÁRIO)
        $viewConsultations = $auth->createPermission('viewConsultations');
        $viewConsultations->description = 'Visualizar consultas';
        $auth->add($viewConsultations);

        // UPDATE - Atualizar consultas (VETERINÁRIO)
        $updateConsultation = $auth->createPermission('updateConsultation');
        $updateConsultation->description = 'Atualizar consultas';
        $auth->add($updateConsultation);

        // DELETE - Eliminar consultas (VETERINÁRIO)
        $deleteConsultation = $auth->createPermission('deleteConsultation');
        $deleteConsultation->description = 'Eliminar consultas';
        $auth->add($deleteConsultation);

        //----------------------------------------------------------------
        //GERIR ANIMAIS (VETERINÁRIO)

        // CREATE - Criar animais (VETERINÁRIO)
        $createAnimal = $auth->createPermission('createAnimal');
        $createAnimal->description = 'Criar animais';
        $auth->add($createAnimal);

        // READ - Visualizar animais (VETERINÁRIO)
        $viewAnimals = $auth->createPermission('viewAnimals');
        $viewAnimals->description = 'Visualizar animais';
        $auth->add($viewAnimals);

        // UPDATE - Atualizar animais (VETERINÁRIO)
        $updateAnimal = $auth->createPermission('updateAnimal');
        $updateAnimal->description = 'Atualizar animais';
        $auth->add($updateAnimal);

        // DELETE - Eliminar animais (VETERINÁRIO)
        $deleteAnimal = $auth->createPermission('deleteAnimal');
        $deleteAnimal->description = 'Eliminar animais';
        $auth->add($deleteAnimal);

        //----------------------------------------------------------------
        //GERIR RAÇAS DE ANIMAIS (VETERINÁRIO)

        // CREATE - Criar raças de animais (VETERINÁRIO)
        $createBreed = $auth->createPermission('createBreed');
        $createBreed->description = 'Criar raças de animais';
        $auth->add($createBreed);

        // READ - Visualizar raças de animais (VETERINÁRIO)
        $viewBreeds = $auth->createPermission('viewBreeds');
        $viewBreeds->description = 'Visualizar raças de animais';
        $auth->add($viewBreeds);

        // UPDATE - Atualizar raças de animais (VETERINÁRIO)
        $updateBreed = $auth->createPermission('updateBreed');
        $updateBreed->description = 'Atualizar raças de animais';
        $auth->add($updateBreed);

        // DELETE - Eliminar raças de animais (VETERINÁRIO)
        $deleteBreed = $auth->createPermission('deleteBreed');
        $deleteBreed->description = 'Eliminar raças de animais';
        $auth->add($deleteBreed);

        //----------------------------------------------------------------
        //GERIR ESPÉCIES DE ANIMAIS (VETERINÁRIO)

        // CREATE - Criar espécies de animais (VETERINÁRIO)
        $createSpecies = $auth->createPermission('createSpecies');
        $createSpecies->description = 'Criar espécies de animais';
        $auth->add($createSpecies);

        // READ - Visualizar espécies de animais (VETERINÁRIO)
        $viewSpecies = $auth->createPermission('viewSpecies');
        $viewSpecies->description = 'Visualizar espécies de animais';
        $auth->add($viewSpecies);

        // UPDATE - Atualizar espécies de animais (VETERINÁRIO)
        $updateSpecies = $auth->createPermission('updateSpecies');
        $updateSpecies->description = 'Atualizar espécies de animais';
        $auth->add($updateSpecies);

        // DELETE - Eliminar espécies de animais (VETERINÁRIO)
        $deleteSpecies = $auth->createPermission('deleteSpecies');
        $deleteSpecies->description = 'Eliminar espécies de animais';
        $auth->add($deleteSpecies);
        //----------------------------------------------------------------
        //GERIR CATEGORIAS DE MEDICAÇÕES (VETERINÁRIO)

        // CREATE - Criar categorias de medicações (VETERINÁRIO)
        $createMedicationCategory = $auth->createPermission('createMedicationCategory');
        $createMedicationCategory->description = 'Criar categorias de medicações';
        $auth->add($createMedicationCategory);

        //READ - Visualizar categorias de medicações (VETERINÁRIO)
        $viewMedicationCategories = $auth->createPermission('viewMedicationCategories');
        $viewMedicationCategories->description = 'Visualizar categorias de medicações';
        $auth->add($viewMedicationCategories);

        // UPDATE - Atualizar categorias de medicações (VETERINÁRIO)
        $updateMedicationCategory = $auth->createPermission('updateMedicationCategory');
        $updateMedicationCategory->description = 'Atualizar categorias de medicações';
        $auth->add($updateMedicationCategory);

        //DELETE - Eliminar categorias de medicações (VETERINÁRIO)
        $deleteMedicationCategory = $auth->createPermission('deleteMedicationCategory');
        $deleteMedicationCategory->description = 'Eliminar categorias de medicações';
        $auth->add($deleteMedicationCategory);

        //----------------------------------------------------------------
        //GERIR MEDICAÇÕES (VETERINÁRIO)

        //CREATE - Atribuir medicação (VETERINÁRIO)
        $assignMedication = $auth->createPermission('assignMedication');
        $assignMedication->description = 'Atribuir medicação';
        $auth->add($assignMedication);

        //READ - Visualizar medicação (VETERINÁRIO)
        $viewMedications = $auth->createPermission('viewMedications');
        $viewMedications->description = 'Visualizar medicação';
        $auth->add($viewMedications);

        //UPDATE - Atualizar medicação (VETERINÁRIO)
        $updateMedication = $auth->createPermission('updateMedication');
        $updateMedication->description = 'Atualizar medicação';
        $auth->add($updateMedication);

        //DELETE - Eliminar medicação (VETERINÁRIO)
        $deleteMedication = $auth->createPermission('deleteMedication');
        $deleteMedication->description = 'Eliminar medicação';
        $auth->add($deleteMedication);

        //----------------------------------------------------------------
        //GERIR MARCAÇÕES (RECECIONISTA)

        // CREATE - Criar marcações (RECECIONISTA)
        $createAppointment = $auth->createPermission('createAppointment');
        $createAppointment->description = 'Criar marcações';
        $auth->add($createAppointment);

        // READ - Visualizar marcações (RECECIONISTA)
        $viewAppointments = $auth->createPermission('viewAppointments');
        $viewAppointments->description = 'Visualizar marcações';
        $auth->add($viewAppointments);

        // UPDATE - Atualizar marcações (RECECIONISTA)
        $updateAppointment = $auth->createPermission('updateAppointment');
        $updateAppointment->description = 'Atualizar marcações';
        $auth->add($updateAppointment);

        // DELETE - Eliminar marcações (RECECIONISTA)
        $deleteAppointment = $auth->createPermission('deleteAppointment');
        $deleteAppointment->description = 'Eliminar marcações';
        $auth->add($deleteAppointment);

        //----------------------------------------------------------------
        //GERIR CLIENTES (RECECIONISTA)

        // CREATE - Criar clientes (RECECIONISTA)
        $createClient = $auth->createPermission('createClient');
        $createClient->description = 'Criar clientes';
        $auth->add($createClient);

        // READ - Visualizar clientes (RECECIONISTA)
        $viewClients = $auth->createPermission('viewClients');
        $viewClients->description = 'Visualizar clientes';
        $auth->add($viewClients);

        // UPDATE - Atualizar clientes (RECECIONISTA)
        $updateClient = $auth->createPermission('updateClient');
        $updateClient->description = 'Atualizar clientes';
        $auth->add($updateClient);

        // DELETE - Eliminar clientes (RECECIONISTA)
        $deleteClient = $auth->createPermission('deleteClient');
        $deleteClient->description = 'Eliminar clientes';
        $auth->add($deleteClient);

        //--------------------------------------------------------
        //GERIR FATURAS (Rececionista)

        // CREATE - Criar faturas (RECECIONISTA)
        $createInvoice = $auth->createPermission('createInvoice');
        $createInvoice->description = 'Criar faturas';
        $auth->add($createInvoice);

        // READ - Visualizar faturas (RECECIONISTA e CLIENTE)
        $viewInvoices = $auth->createPermission('viewInvoices');
        $viewInvoices->description = 'Visualizar faturas';
        $auth->add($viewInvoices);

        // DELETE - Eliminar faturas (RECECIONISTA)
        $deleteInvoice = $auth->createPermission('deleteInvoice');
        $deleteInvoice->description = 'Eliminar faturas';
        $auth->add($deleteInvoice);

        //--------------------------------------------------------
        //PERMISSÕES ESPECÍFICAS DO CLIENTE:

        //Editar notas do dono do animal (Cliente)
        $editOwnerNotes = $auth->createPermission('editOwnerNotes');
        $editOwnerNotes->description = 'Editar notas dono do seu animal';
        $auth->add($editOwnerNotes);

        //
    //========================================================================
    //========================================================================
    //Roles:

    //administrador
        $admin = $auth->createRole('admin');
        $auth->add($admin);

        $auth->addChild($admin, $createUser);
        $auth->addChild($admin, $viewUsers);
        $auth->addChild($admin, $updateUser);
        $auth->addChild($admin, $deleteUser);

        $auth->addChild($admin, $createProduct);
        $auth->addChild($admin, $viewProducts);
        $auth->addChild($admin, $updateProduct);
        $auth->addChild($admin, $deleteProduct);

        $auth->addChild($admin, $createInventory);
        $auth->addChild($admin, $viewInventory);
        $auth->addChild($admin, $updateInventory);
        $auth->addChild($admin, $deleteInventory);

    //===============================================
    //veterinario
        $veterinario = $auth->createRole('veterinario');
        $auth->add($veterinario);

        $auth->addChild($veterinario, $createConsultation);
        $auth->addChild($veterinario, $viewConsultations);
        $auth->addChild($veterinario, $updateConsultation);
        $auth->addChild($veterinario, $deleteConsultation);

        $auth->addChild($veterinario, $createAnimal);
        $auth->addChild($veterinario, $viewAnimals);
        $auth->addChild($veterinario, $updateAnimal);
        $auth->addChild($veterinario, $deleteAnimal);

        $auth->addChild($veterinario, $assignMedication);

    //===============================================
    //rececionista
        $recepcionista = $auth->createRole('recepcionista');
        $auth->add($recepcionista);

        $auth->addChild($recepcionista, $viewConsultations);

        $auth->addChild($recepcionista, $createAppointment);
        $auth->addChild($recepcionista, $viewAppointments);
        $auth->addChild($recepcionista, $updateAppointment);
        $auth->addChild($recepcionista, $deleteAppointment);

        $auth->addChild($recepcionista, $createClient);
        $auth->addChild($recepcionista, $viewClients);
        $auth->addChild($recepcionista, $updateClient);
        $auth->addChild($recepcionista, $deleteClient);

    //===============================================
    //cliente (Dono do animal)
        $cliente = $auth->createRole('cliente');
        $auth->add($cliente);

        // Reutiliza viewAnimals para "Visualizar informações do seu animal"
        $auth->addChild($cliente, $viewAnimals);

        // Usa updateClient para "Editar as suas informações"
        $auth->addChild($cliente, $updateClient);

        // Reutiliza viewConsultations para "Visualizar histórico de consultas"
        $auth->addChild($cliente, $viewConsultations);

        // Reutiliza viewInvoices para "Visualizar suas faturas"
        $auth->addChild($cliente, $viewInvoices);

        // Permissão específica do cliente para editar notas do dono do animal
        $auth->addChild($cliente, $editOwnerNotes);

    //===============================================
    // Herança de Roles
        $auth->addChild($admin, $veterinario);
        $auth->addChild($admin, $recepcionista);
        $auth->addChild($admin, $cliente);

        $auth->addChild($veterinario, $cliente);
        $auth->addChild($recepcionista, $cliente);


        //Mensagem pra dar feedback que rodou o script
        echo "✅ RBAC inicializado com sucesso ✅\n";
    }



}