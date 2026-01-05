<?php

namespace common\tests\unit\models;

use common\models\Userprofile;
use common\models\User;

/**
 * Testes do modelo Userprofile.
 * 
 * Esta suite de testes verifica o comportamento do modelo Userprofile,
 * que representa o perfil extendido de um usuário com informações adicionais
 * como NIF, telemovel, e relacionamentos com o modelo User.
 */
class UserprofileTest extends \Codeception\Test\Unit
{
    /**
     * Testa se é possível criar e salvar um perfil de usuário.
     * 
     * Cenário: Criação de um novo perfil com user_id, NIF e telemovel.
     * Expectativa: O perfil deve ser salvo com sucesso no banco de dados.
     */
    public function testDeveCriarEGravarPerfilDeUsuario()
    {
        // Primeiro cria um usuário
        $user = new User();
        $user->username = 'testuser_' . time();
        $user->email = 'testuser_' . time() . '@test.com';
        $user->setPassword('password123');
        $user->generateAuthKey();
        $user->status = User::STATUS_ACTIVE;
        $this->assertTrue($user->save(false), 'Falha ao criar usuário');

        // Agora cria o perfil associado com todos os campos obrigatórios
        $profile = new Userprofile();
        $profile->user_id = $user->id;
        $profile->nomecompleto = 'Teste Usuario';
        $profile->nif = '123456789';
        $profile->telemovel = '912345678';
        $profile->dtanascimento = '1990-01-01';

        $this->assertTrue($profile->save(), 'Falha ao criar perfil: ' . json_encode($profile->errors));
        
        // Limpa os dados criados
        $profile->delete();
        $user->delete();
    }

    /**
     * Testa o relacionamento entre User e Userprofile.
     * 
     * Cenário: Busca um usuário e verifica se o relacionamento com o perfil está configurado.
     * Expectativa: O perfil do usuário deve estar acessível via relação 'profile'.
     */
    public function testDeveCarregarRelacionamentoEntreUserEProfile()
    {
        // Cria um usuário
        $user = new User();
        $user->username = 'testuser_rel_' . time();
        $user->email = 'testuser_rel_' . time() . '@test.com';
        $user->setPassword('password123');
        $user->generateAuthKey();
        $user->status = User::STATUS_ACTIVE;
        $this->assertTrue($user->save(false), 'Falha ao criar usuário');

        // Cria um perfil para o usuário com todos os campos obrigatórios
        $profile = new Userprofile();
        $profile->user_id = $user->id;
        $profile->nomecompleto = 'Teste Usuario Relacionamento';
        $profile->nif = '266144144'; // Valid Portuguese NIF
        $profile->telemovel = '919876543';
        $profile->dtanascimento = '1995-05-15';
        $this->assertTrue($profile->save(), 'Falha ao criar perfil: ' . json_encode($profile->errors));

        // Recarrega o perfil do banco de dados
        $profile->refresh();
        
        // Verifica o relacionamento Userprofile -> User
        $this->assertNotNull($profile->user, 'Relacionamento user não está configurado');
        $this->assertEquals($user->id, $profile->user->id);
        
        // Limpa os dados criados
        $profile->delete();
        $user->delete();
    }
}
