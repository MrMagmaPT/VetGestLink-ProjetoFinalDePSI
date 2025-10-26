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
        //Permissões:

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

        //----------------------------------------------------------------
        //GERIR METODOS DE PAGAMENTO (ADMIN)

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

        //----------------------------------------------------------------
        //GERIR CATEGORIAS DE MEDICAMENTOS (ADMIN)

        // CREATE - Criar categorias de medicamentos (ADMIN)
        $createMedicationCategory = $auth->createPermission('createMedicationCategory');
        $createMedicationCategory->description = 'Criar categorias de medicamentos';
        $auth->add($createMedicationCategory);

        // READ - Visualizar categorias de medicamentos (ADMIN)
        $viewMedicationCategories = $auth->createPermission('viewMedicationCategories');
        $viewMedicationCategories->description = 'Visualizar categorias de medicamentos';
        $auth->add($viewMedicationCategories);

        // UPDATE - Atualizar categorias de medicamentos (ADMIN)
        $updateMedicationCategory = $auth->createPermission('updateMedicationCategory');
        $updateMedicationCategory->description = 'Atualizar categorias de medicamentos';
        $auth->add($updateMedicationCategory);

        // DELETE - Eliminar categorias de medicamentos (ADMIN)
        $deleteMedicationCategory = $auth->createPermission('deleteMedicationCategory');
        $deleteMedicationCategory->description = 'Eliminar categorias de medicamentos';
        $auth->add($deleteMedicationCategory);

        //----------------------------------------------------------------
        //GERIR MEDICAMENTOS (ADMIN)

        // CREATE - Criar medicamentos (ADMIN)
        $createMedication = $auth->createPermission('createMedication');
        $createMedication->description = 'Criar medicamentos';
        $auth->add($createMedication);

        // READ - Visualizar medicamentos (ADMIN e VETERINÁRIO)
        $viewMedications = $auth->createPermission('viewMedications');
        $viewMedications->description = 'Visualizar medicamentos';
        $auth->add($viewMedications);

        // UPDATE - Atualizar medicamentos (ADMIN)
        $updateMedication = $auth->createPermission('updateMedication');
        $updateMedication->description = 'Atualizar medicamentos';
        $auth->add($updateMedication);

        // DELETE - Eliminar medicamentos (ADMIN)
        $deleteMedication = $auth->createPermission('deleteMedication');
        $deleteMedication->description = 'Eliminar medicamentos';
        $auth->add($deleteMedication);

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
        //ATRIBUIR MEDICAÇÃO (VETERINÁRIO)

        // CREATE - Atribuir medicação a consultas (VETERINÁRIO)
        $assignMedication = $auth->createPermission('assignMedication');
        $assignMedication->description = 'Atribuir medicação a consultas';
        $auth->add($assignMedication);

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
        //GERIR MORADAS (RECECIONISTA E CLIENTE)

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

        //----------------------------------------------------------------
        //GERIR FATURAS (RECECIONISTA)

        // CREATE - Criar faturas (RECECIONISTA)
        $createInvoice = $auth->createPermission('createInvoice');
        $createInvoice->description = 'Criar faturas';
        $auth->add($createInvoice);

        // READ - Visualizar faturas (RECECIONISTA e CLIENTE)
        $viewInvoices = $auth->createPermission('viewInvoices');
        $viewInvoices->description = 'Visualizar faturas';
        $auth->add($viewInvoices);

        // UPDATE - Atualizar faturas (RECECIONISTA)
        $updateInvoice = $auth->createPermission('updateInvoice');
        $updateInvoice->description = 'Atualizar faturas';
        $auth->add($updateInvoice);

        // DELETE - Eliminar faturas (RECECIONISTA)
        $deleteInvoice = $auth->createPermission('deleteInvoice');
        $deleteInvoice->description = 'Eliminar faturas';
        $auth->add($deleteInvoice);

        //----------------------------------------------------------------
        //PERMISSÕES ESPECÍFICAS DO CLIENTE:

        //Editar notas do dono do animal (Cliente)
        $editOwnerNotes = $auth->createPermission('editOwnerNotes');
        $editOwnerNotes->description = 'Editar notas dono do seu animal';
        $auth->add($editOwnerNotes);

        //========================================================================
        //PERMISSOES BACKEND (ADMIN, VETERINARIO, RECECIONISTA):

        $backendAccess = $auth->createPermission('backendAccess');
        $backendAccess->description = 'Acesso ao backend';
        $auth->add($backendAccess);

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

        $auth->addChild($admin, $createPaymentMethod);
        $auth->addChild($admin, $viewPaymentMethods);
        $auth->addChild($admin, $updatePaymentMethod);
        $auth->addChild($admin, $deletePaymentMethod);

        $auth->addChild($admin, $createMedicationCategory);
        $auth->addChild($admin, $viewMedicationCategories);
        $auth->addChild($admin, $updateMedicationCategory);
        $auth->addChild($admin, $deleteMedicationCategory);

        $auth->addChild($admin, $createMedication);
        $auth->addChild($admin, $viewMedications);
        $auth->addChild($admin, $updateMedication);
        $auth->addChild($admin, $deleteMedication);

        $auth->addChild($admin, $backendAccess);

        //===============================================
        //veterinario
        $veterinario = $auth->createRole('veterinario');
        $auth->add($veterinario);

        $auth->addChild($veterinario, $createConsultation);
        $auth->addChild($veterinario, $viewConsultations);
        $auth->addChild($veterinario, $updateConsultation);
        $auth->addChild($veterinario, $deleteConsultation);

        $auth->addChild($veterinario, $assignMedication);
        $auth->addChild($veterinario, $viewMedications);

        $auth->addChild($veterinario, $createAnimal);
        $auth->addChild($veterinario, $viewAnimals);
        $auth->addChild($veterinario, $updateAnimal);
        $auth->addChild($veterinario, $deleteAnimal);

        $auth->addChild($veterinario, $createBreed);
        $auth->addChild($veterinario, $viewBreeds);
        $auth->addChild($veterinario, $updateBreed);
        $auth->addChild($veterinario, $deleteBreed);

        $auth->addChild($veterinario, $createSpecies);
        $auth->addChild($veterinario, $viewSpecies);
        $auth->addChild($veterinario, $updateSpecies);
        $auth->addChild($veterinario, $deleteSpecies);

        $auth->addChild($veterinario, $backendAccess);

        //===============================================
        //rececionista
        $rececionista = $auth->createRole('rececionista');
        $auth->add($rececionista);

        $auth->addChild($rececionista, $viewConsultations);

        $auth->addChild($rececionista, $createAppointment);
        $auth->addChild($rececionista, $viewAppointments);
        $auth->addChild($rececionista, $updateAppointment);
        $auth->addChild($rececionista, $deleteAppointment);

        $auth->addChild($rececionista, $createAddress);
        $auth->addChild($rececionista, $viewAddresses);
        $auth->addChild($rececionista, $updateAddress);
        $auth->addChild($rececionista, $deleteAddress);

        $auth->addChild($rececionista, $createClient);
        $auth->addChild($rececionista, $viewClients);
        $auth->addChild($rececionista, $updateClient);
        $auth->addChild($rececionista, $deleteClient);

        $auth->addChild($rececionista, $createInvoice);
        $auth->addChild($rececionista, $viewInvoices);
        $auth->addChild($rececionista, $updateInvoice);
        $auth->addChild($rececionista, $deleteInvoice);

        $auth->addChild($rececionista, $viewPaymentMethods);

        $auth->addChild($rececionista, $backendAccess);

        //===============================================
        //cliente (Dono do animal)
        $cliente = $auth->createRole('cliente');
        $auth->add($cliente);

        $auth->addChild($cliente, $viewAnimals);
        $auth->addChild($cliente, $updateClient);
        $auth->addChild($cliente, $viewConsultations);
        $auth->addChild($cliente, $viewInvoices);

        $auth->addChild($cliente, $createAddress);
        $auth->addChild($cliente, $viewAddresses);
        $auth->addChild($cliente, $updateAddress);
        $auth->addChild($cliente, $deleteAddress);

        $auth->addChild($cliente, $editOwnerNotes);

        //===============================================
        // Herança de Roles
        $auth->addChild($admin, $veterinario);
        $auth->addChild($admin, $rececionista);
        $auth->addChild($admin, $cliente);

        $auth->addChild($veterinario, $cliente);
        $auth->addChild($rececionista, $cliente);

        //Mensagem pra dar feedback que rodou o script
        echo "✅ RBAC inicializado com sucesso ✅\n";
    }
}
