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

        //Permissões:
        //========================================================================
        //GERIR UTILIZADORES (ADMIN)(Rececionista)

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

        //-----------------------------------------------------------------------//
        //          GERIR METODOS DE PAGAMENTO (ADMIN)(Cliente)                 //
        //--------------------------------------------------------------------//

        // CREATE - Criar métodos de pagamento (ADMIN)
        $createPaymentMethod = $auth->createPermission('createPaymentMethod');
        $createPaymentMethod->description = 'Criar métodos de pagamento';
        $auth->add($createPaymentMethod);

        // READ - Visualizar métodos de pagamento (ADMIN)
        $viewPaymentMethods = $auth->createPermission('viewPaymentMethods');
        $viewPaymentMethods->description = 'Visualizar métodos de pagamento';
        $auth->add($viewPaymentMethods);

        // UPDATE - Atualizar métodos de pagamento (ADMIN)
        $updatePaymentMethod = $auth->createPermission('updatePaymentMethod');
        $updatePaymentMethod->description = 'Atualizar métodos de pagamento';
        $auth->add($updatePaymentMethod);

        // DELETE - Eliminar métodos de pagamento (ADMIN)
        $deletePaymentMethod = $auth->createPermission('deletePaymentMethod');
        $deletePaymentMethod->description = 'Eliminar métodos de pagamento';
        $auth->add($deletePaymentMethod);
        //------------------------------------------------------------------//
        //                  GERIR CATEGORIAS DE MEDICAMENTOS (ADMIN)       //
        //----------------------------------------------------------------//

        // CREATE - Criar categoria de medicamento (ADMIN)
        $createMedicationCategory = $auth->createPermission('createMedicationCategory');
        $createMedicationCategory->description = 'Criar categoria de medicamento';
        $auth->add($createMedicationCategory);

        // READ - Visualizar categoria de medicamento (ADMIN)
        $viewMedicationCategories = $auth->createPermission('viewMedicationCategories');
        $viewMedicationCategories->description = 'Visualizar categoria de medicamento';
        $auth->add($viewMedicationCategories);

        // UPDATE - Atualizar categoria de medicamento (ADMIN)
        $updateMedicationCategory = $auth->createPermission('updateMedicationCategory');
        $updateMedicationCategory->description = 'Atualizar categoria de medicamento';
        $auth->add($updateMedicationCategory);

        // DELETE - Eliminar categoria de medicamento (ADMIN)
        $deleteMedicationCategory = $auth->createPermission('deleteMedicationCategory');
        $deleteMedicationCategory->description = 'Eliminar categoria de medicamento';
        $auth->add($deleteMedicationCategory);

        //-------------------------------------------------------------------//
        //                      GERIR MEDICAMENTOS (ADMIN)                  //
        //-----------------------------------------------------------------//

        // CREATE - Criar medicamento (ADMIN)
        $createMedication = $auth->createPermission('createMedication');
        $createMedication->description = 'Criar medicamento';
        $auth->add($createMedication);

        // READ - Visualizar medicamento (ADMIN e VETERINÁRIO)
        $viewMedications = $auth->createPermission('viewMedications');
        $viewMedications->description = 'Visualizar medicamento';
        $auth->add($viewMedications);

        // UPDATE - Atualizar medicamento (ADMIN)
        $updateMedication = $auth->createPermission('updateMedication');
        $updateMedication->description = 'Atualizar medicamento';
        $auth->add($updateMedication);

        // DELETE - Eliminar medicamento (ADMIN)
        $deleteMedication = $auth->createPermission('deleteMedication');
        $deleteMedication->description = 'Eliminar medicamento';
        $auth->add($deleteMedication);
        //------------------------------------------------------------------//
        //                      GERIR SERVICOS (ADMIN)                     //
        //----------------------------------------------------------------//  

        // CREATE - Criar serviço (ADMIN)
        $createService = $auth->createPermission('createService');
        $createService->description = 'Criar serviço';
        $auth->add($createService);

        // READ - Visualizar serviço (ADMIN)
        $viewServices = $auth->createPermission('viewServices');
        $viewServices->description = 'Visualizar serviço';
        $auth->add($viewServices);

        // UPDATE - Atualizar serviço (ADMIN)
        $updateService = $auth->createPermission('updateService');
        $updateService->description = 'Atualizar serviço';
        $auth->add($updateService);

        // DELETE - Eliminar serviço (ADMIN)
        $deleteService = $auth->createPermission('deleteService');
        $deleteService->description = 'Eliminar serviço';
        $auth->add($deleteService);

        //------------------------------------------------------------------//
        //             ATRIBUIR MEDICAÇÃO (VETERINÁRIO)                     //    
        //------------------------------------------------------------------//

        // CREATE - Atribuir medicação a consultas (VETERINÁRIO)
        $assignMedication = $auth->createPermission('assignMedication');
        $assignMedication->description = 'Atribuir medicação a consultas';
        $auth->add($assignMedication);

        //------------------------------------------------------------------//
        //             GERIR ANIMAIS (VETERINÁRIO)(Rececionsita)           //
        //----------------------------------------------------------------//

        // CREATE - Criar animal (VETERINÁRIO)(Rececionsita)
        $createAnimal = $auth->createPermission('createAnimal');
        $createAnimal->description = 'Criar animal';
        $auth->add($createAnimal);

        // READ - Visualizar animal (VETERINÁRIO)
        $viewAnimals = $auth->createPermission('viewAnimals');
        $viewAnimals->description = 'Visualizar animal';
        $auth->add($viewAnimals);

        // UPDATE - Atualizar animal (VETERINÁRIO)
        $updateAnimal = $auth->createPermission('updateAnimal');
        $updateAnimal->description = 'Atualizar animal';
        $auth->add($updateAnimal);

        // DELETE - Eliminar animal (VETERINÁRIO)
        $deleteAnimal = $auth->createPermission('deleteAnimal');
        $deleteAnimal->description = 'Eliminar animal';
        $auth->add($deleteAnimal);

        //-------------------------------------------------------------------//
        //              GERIR RAÇAS DE ANIMAIS (VETERINÁRIO)                //
        //-----------------------------------------------------------------//

        // CREATE - Criar raças de animal (VETERINÁRIO)
        $createBreed = $auth->createPermission('createBreed');
        $createBreed->description = 'Criar raças de animal';
        $auth->add($createBreed);

        // READ - Visualizar raças de animal (VETERINÁRIO)
        $viewBreeds = $auth->createPermission('viewBreeds');
        $viewBreeds->description = 'Visualizar raças de animal';
        $auth->add($viewBreeds);

        // UPDATE - Atualizar raças de animal (VETERINÁRIO)
        $updateBreed = $auth->createPermission('updateBreed');
        $updateBreed->description = 'Atualizar raças de animal';
        $auth->add($updateBreed);

        // DELETE - Eliminar raças de animal (VETERINÁRIO)
        $deleteBreed = $auth->createPermission('deleteBreed');
        $deleteBreed->description = 'Eliminar raças de animal';
        $auth->add($deleteBreed);

        //----------------------------------------------------------------------//
        //              GERIR ESPÉCIES DE ANIMAIS (VETERINÁRIO)                //
        //--------------------------------------------------------------------//

        // CREATE - Criar espécies de animal (VETERINÁRIO)
        $createSpecies = $auth->createPermission('createSpecies');
        $createSpecies->description = 'Criar espécies de animal';
        $auth->add($createSpecies);

        // READ - Visualizar espécies de animal (VETERINÁRIO)
        $viewSpecies = $auth->createPermission('viewSpecies');
        $viewSpecies->description = 'Visualizar espécies de animal';
        $auth->add($viewSpecies);

        // UPDATE - Atualizar espécies de animal (VETERINÁRIO)
        $updateSpecies = $auth->createPermission('updateSpecies');
        $updateSpecies->description = 'Atualizar espécies de animal';
        $auth->add($updateSpecies);

        // DELETE - Eliminar espécies de animal (VETERINÁRIO)
        $deleteSpecies = $auth->createPermission('deleteSpecies');
        $deleteSpecies->description = 'Eliminar espécies de animal';
        $auth->add($deleteSpecies);

        //-------------------------------------------------------------------//
        //              GERIR MARCAÇÕES (RECECIONISTA)                      //
        //-----------------------------------------------------------------//
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

        //--------------------------------------------------------------------//
        //                 GERIR MORADAS (RECECIONISTA E CLIENTE)            //
        //-------------------------------------------------------------------// 

        // CREATE - Criar moradas (RECECIONISTA e CLIENTE)
        $createAddress = $auth->createPermission('createAddress');
        $createAddress->description = 'Criar moradas';
        $auth->add($createAddress);

        // READ - Visualizar moradas (RECECIONISTA e CLIENTE)
        $viewAddresses = $auth->createPermission('viewAddresses');
        $viewAddresses->description = 'Visualizar moradas';
        $auth->add($viewAddresses);

        // UPDATE - Atualizar moradas (RECECIONISTA e CLIENTE)
        $updateAddress = $auth->createPermission('updateAddress');
        $updateAddress->description = 'Atualizar moradas';
        $auth->add($updateAddress);
 
        // DELETE - Eliminar moradas (RECECIONISTA e CLIENTE)
        $deleteAddress = $auth->createPermission('deleteAddress');
        $deleteAddress->description = 'Eliminar moradas';
        $auth->add($deleteAddress);

        //-------------------------------------------------------------------//
        //                 GERIR CLIENTES (RECECIONISTA)                    //
        //-----------------------------------------------------------------//

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

        //--------------------------------------------------------------------//
        //                     GERIR FATURAS (RECECIONISTA)                   //
        //--------------------------------------------------------------------//

        // CREATE - Criar fatura (RECECIONISTA)
        $createInvoice = $auth->createPermission('createInvoice');
        $createInvoice->description = 'Criar fatura';
        $auth->add($createInvoice);

        // READ - Visualizar fatura (RECECIONISTA e CLIENTE)
        $viewInvoices = $auth->createPermission('viewInvoices');
        $viewInvoices->description = 'Visualizar fatura';
        $auth->add($viewInvoices);

        // UPDATE - Atualizar fatura (RECECIONISTA)
        $updateInvoice = $auth->createPermission('updateInvoice');
        $updateInvoice->description = 'Atualizar fatura';
        $auth->add($updateInvoice);

        // DELETE - Eliminar fatura (RECECIONISTA)
        $deleteInvoice = $auth->createPermission('deleteInvoice');
        $deleteInvoice->description = 'Eliminar fatura';
        $auth->add($deleteInvoice);

        //=========================================================================//
        //          PERMISSÕES ESPECÍFICAS DO CLIENTE:(NOTAS DO ANIMAL)           //
        //=======================================================================//

        //CREATE - Criar notas animal (CLIENTE)
        $createNotes = $auth->createPermission('createNotes');
        $createNotes->description = 'Criar notas dono do seu animal';  
        $auth->add($createNotes);

        //READ - Visualizar notas animal (CLIENTE)
        $viewNotes = $auth->createPermission('viewNotes');
        $viewNotes->description = 'Visualizar notas dono do seu animal';
        $auth->add($viewNotes);

        //UPDATE - Editar notas animal (CLIENTE)
        $updateNotes = $auth->createPermission('updateNotes');
        $updateNotes->description = 'Atualizar notas dono do seu animal';
        $auth->add($updateNotes);

        //DELETE - Eliminar notas animal (CLIENTE)
        $deleteNotes = $auth->createPermission('deleteNotes');
        $deleteNotes->description = 'Eliminar notas dono do seu animal';
        $auth->add($deleteNotes);

        //===========================================================================//
        //              PERMISSÕES ESPECÍFICAS DO CLIENTE:(LEMBRETES)               //
        //========================================================================//

        //CREATE - Criar lembretes (CLIENTE)
        $createReminders = $auth->createPermission('createReminders');
        $createReminders->description = 'Criar lembretes';
        $auth->add($createReminders);

        //READ - Visualizar lembretes (CLIENTE)
        $viewReminders = $auth->createPermission('viewReminders'); 
        $viewReminders->description = 'Visualizar lembretes';
        $auth->add($viewReminders);

        //UPDATE - Editar lembretes (CLIENTE)
        $updateReminders = $auth->createPermission('updateReminders');
        $updateReminders->description = 'Atualizar lembretes';
        $auth->add($updateReminders);

        //DELETE - Eliminar lembretes (CLIENTE)
        $deleteReminders = $auth->createPermission('deleteReminders');  
        $deleteReminders->description = 'Eliminar lembretes';
        $auth->add($deleteReminders);

        //=========================================================================//
        //                   PERMISSÕES ESPECÍFICAS DO CLIENTE:(MORADAS)          //
        //========================================================================//


        // DELETE - Eliminar morada moradas secudarias (CLIENTE)
        $deleteSecondaryAddress = $auth->createPermission('deleteSecondaryAddress');
        $deleteSecondaryAddress->description = 'Eliminar moradas';
        $auth->add($deleteSecondaryAddress);

        //=========================================================================
        //PERMISSÕES ESPECÍFICAS DO CLIENTE:(PAGAR FATURAS)

        //PAGAR FATURAS (CLIENTE)
        $payInvoices = $auth->createPermission('payInvoices');
        $payInvoices->description = 'Pagar faturas';
        $auth->add($payInvoices);
        //=========================================================================//
        //              PERMISSOES BACKEND (ADMIN, VETERINARIO, RECECIONISTA):    //
        //========================================================================//

        $backendAccess = $auth->createPermission('backendAccess');
        $backendAccess->description = 'Acesso ao backend';
        $auth->add($backendAccess);

        //========================================================================//
        //========================================================================//
        //                              ROLES:                                    //
        //========================================================================//
        //========================================================================//

        //========================================================================
        //ADMINISTRADOR
        $admin = $auth->createRole('admin');
        $auth->add($admin);

        //CRUD Utilizadores
        $auth->addChild($admin, $createUser);
        $auth->addChild($admin, $viewUsers);
        $auth->addChild($admin, $updateUser);
        $auth->addChild($admin, $deleteUser);

        //CRUD Métodos de Pagamento
        $auth->addChild($admin, $createPaymentMethod);
        $auth->addChild($admin, $viewPaymentMethods);
        $auth->addChild($admin, $updatePaymentMethod);
        $auth->addChild($admin, $deletePaymentMethod);

        //CRUD Categorias de Medicamentos
        $auth->addChild($admin, $createMedicationCategory);
        $auth->addChild($admin, $viewMedicationCategories);
        $auth->addChild($admin, $updateMedicationCategory);
        $auth->addChild($admin, $deleteMedicationCategory);

        //CRUD Medicamentos
        $auth->addChild($admin, $createMedication);
        $auth->addChild($admin, $viewMedications);
        $auth->addChild($admin, $updateMedication);
        $auth->addChild($admin, $deleteMedication);

        //CRUD Serviços
        $auth->addChild($admin, $createService);
        $auth->addChild($admin, $viewServices);
        $auth->addChild($admin, $updateService);
        $auth->addChild($admin, $deleteService);

        //PERMISSÃO BACKEND
        $auth->addChild($admin, $backendAccess);
        //========================================================================
        //VETERINÁRIO
        $veterinario = $auth->createRole('veterinario');
        $auth->add($veterinario);

        //VER MEDICAMENTOS
        $auth->addChild($veterinario, $viewMedications);


        //VER SERVIÇOS
        $auth->addChild($veterinario, $viewServices);

        //ATRIBUIR MEDICAÇÃO NA MARCAÇÃO
        $auth->addChild($veterinario, $assignMedication);

        //CRUD Animais(COMPLETO)(SOFT DELETE)
        $auth->addChild($veterinario, $createAnimal);
        $auth->addChild($veterinario, $viewAnimals);
        $auth->addChild($veterinario, $updateAnimal);
        $auth->addChild($veterinario, $deleteAnimal);

        //CRUD Consultas(COMPLETO)(SOFT DELETE)
        $auth->addChild($veterinario, $createAppointment);
        $auth->addChild($veterinario, $viewAppointments);
        $auth->addChild($veterinario, $updateAppointment);
        $auth->addChild($veterinario, $deleteAppointment);

        //CRUD Raças de animais(COMPLETO)(SOFT DELETE)
        $auth->addChild($veterinario, $createBreed);
        $auth->addChild($veterinario, $viewBreeds);
        $auth->addChild($veterinario, $updateBreed);
        $auth->addChild($veterinario, $deleteBreed);

        //CRUD Espécies de animais(COMPLETO)(SOFT DELETE)
        $auth->addChild($veterinario, $createSpecies);
        $auth->addChild($veterinario, $viewSpecies);
        $auth->addChild($veterinario, $updateSpecies);
        $auth->addChild($veterinario, $deleteSpecies);

        //PERMISSÃO BACKEND
        $auth->addChild($veterinario, $backendAccess);
        //========================================================================
        //RECECIONISTA
        $rececionista = $auth->createRole('rececionista');
        $auth->add($rececionista);

        //CRUD Marcações(COMPLETO)(SOFT DELETE)
        $auth->addChild($rececionista, $createAppointment);
        $auth->addChild($rececionista, $viewAppointments);
        $auth->addChild($rececionista, $updateAppointment);
        $auth->addChild($rececionista, $deleteAppointment);

        //CRUD Moradas(COMPLETO)(HARD DELETE)
        $auth->addChild($rececionista, $createAddress);
        $auth->addChild($rececionista, $viewAddresses);
        $auth->addChild($rececionista, $updateAddress);
        $auth->addChild($rececionista, $deleteAddress);

        //CRUD Clientes(COMPLETO)(SOFT DELETE)
        $auth->addChild($rececionista, $createClient);
        $auth->addChild($rececionista, $updateClient);
        $auth->addChild($rececionista, $viewClients);
        $auth->addChild($rececionista, $deleteClient);

        //CRUD Faturas(COMPLETO)(SOFT DELETE)
        $auth->addChild($rececionista, $createInvoice);
        $auth->addChild($rececionista, $viewInvoices);
        $auth->addChild($rececionista, $updateInvoice);
        $auth->addChild($rececionista, $deleteInvoice);

        //CRIAR ANIMAIS E VER ANIMAIS
        $auth->addChild($rececionista, $createAnimal);
        $auth->addChild($rececionista, $viewAnimals);

        //VISUALIZAR OS MÉTODOS DE PAGAMENTO
        $auth->addChild($rececionista, $viewPaymentMethods);

        //VISUALIZAR OS SERVIÇOS
        $auth->addChild($rececionista, $viewServices);

        //PERMISSÃO BACKEND
        $auth->addChild($rececionista, $backendAccess);
        //========================================================================
        //CLIENTE (Dono do animal)
        $cliente = $auth->createRole('cliente');
        $auth->add($cliente);

        //VER Animais✳️-front 
        $auth->addChild($cliente, $viewAnimals);

        //UPDATE da suas informação
        $auth->addChild($cliente, $updateClient);

        //VER suas marcações
        $auth->addChild($cliente, $viewAppointments);

        //VER suas faturas
        $auth->addChild($cliente, $viewInvoices);

        //PAGAR suas faturas
        $auth->addChild($cliente, $payInvoices);

        //CRUD Moradas e delete permanente do das moradas secundárias✳️-front
        $auth->addChild($cliente, $createAddress);
        $auth->addChild($cliente, $viewAddresses);
        $auth->addChild($cliente, $updateAddress);
        $auth->addChild($cliente, $deleteSecondaryAddress);

        //CRUD Notas do animal(COMPLETO)(HARD DELETE)
        $auth->addChild($cliente, $createNotes);
        $auth->addChild($cliente, $viewNotes);
        $auth->addChild($cliente, $updateNotes);
        $auth->addChild($cliente, $deleteNotes);

        //CRUD Lembretes(COMPLETO)(SOFT DELETE)✳️-front 
        $auth->addChild($cliente, $createReminders);
        $auth->addChild($cliente, $viewReminders);
        $auth->addChild($cliente, $updateReminders);    
        $auth->addChild($cliente, $deleteReminders);

        //ver clientes (próprioPerfil)
        $auth->addChild($cliente, $viewClients);
        //========================================================================
        //Mensagem pra dar feedback que rodou o script
        echo "RBAC inicializado com sucesso. Total de permissões: {$this->countPermissions()} | Total de roles: {$this->countRoles()}\n";
    }

    function countPermissions()
    {
        $auth = Yii::$app->authManager;
        return count($auth->getPermissions());
    }

    function countRoles()
    {
        $auth = Yii::$app->authManager;
        return count($auth->getRoles());
    }
}