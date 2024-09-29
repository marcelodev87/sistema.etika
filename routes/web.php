<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Auth::routes();

Route::group(['as' => 'app.', 'middleware' => ['auth', 'role']], function () {

    # página inicial
    Route::get('/', ['uses' => 'DashboardController@index', 'as' => 'index', 'roles' => ['adm', 'usr']]);

    # rota de usuários
    Route::group(['prefix' => 'usuarios', 'as' => 'users.'], function () {
        # screens
        Route::get('/', ['uses' => 'UserController@index', 'as' => 'index', 'roles' => ['adm']]);
        Route::get('/adicionar', ['uses' => 'UserController@create', 'as' => 'create', 'roles' => ['adm']]);
        Route::get('/{user}/editar', ['uses' => 'UserController@edit', 'as' => 'edit', 'roles' => ['adm']]);

        # methods
        Route::post('/', ['uses' => 'UserController@store', 'as' => 'store', 'roles' => ['adm']]);
        Route::put('/{user}', ['uses' => 'UserController@update', 'as' => 'update', 'roles' => ['adm']]);
        Route::patch('/{user}/status', ['uses' => 'UserController@changeStatus', 'as' => 'updateStatus', 'roles' => ['adm']]);
        Route::delete('/{user}', ['uses' => 'UserController@destroy', 'as' => 'delete', 'roles' => ['adm']]);
    });

    # rota de perfil
    Route::group(['prefix' => 'perfil', 'as' => 'profile.'], function () {
        # screens
        Route::get('/', ['uses' => 'ProfileController@index', 'as' => 'index']);

        # methods
        Route::patch('/change-avatar', ['uses' => 'ProfileController@changeAvatar', 'as' => 'update_avatar']);
        Route::patch('/change-password', ['uses' => 'ProfileController@changePassword', 'as' => 'update_password']);
        Route::put('/change-information', ['uses' => 'ProfileController@changeInformation', 'as' => 'update_information']);
        Route::patch('/change-email', ['uses' => 'ProfileController@changeEmail', 'as' => 'update_email']);
    });

    # rota de cliente
    Route::group(['prefix' => 'clientes', 'as' => 'clients.'], function () {
        Route::get('/', ['uses' => 'ClientController@index', 'as' => 'index']);
        Route::get('/{client}', ['uses' => 'ClientController@show', 'as' => 'show', 'roles' => ['adm', 'usr']]);
        Route::post('/', ['uses' => 'ClientController@store', 'as' => 'store', 'roles' => ['adm', 'usr']]);
        Route::put('/{client}', ['uses' => 'ClientController@update', 'as' => 'update', 'roles' => ['adm', 'usr']]);

        // membros
        Route::group(['prefix' => '{client}/membros', 'as' => 'members.'], function () {
            Route::get('/', ['uses' => 'ClientPersonaController@index', 'as' => 'index']);
            Route::post('/', ['uses' => 'ClientPersonaController@store', 'as' => 'store', 'roles' => ['adm', 'usr']]);
            Route::get('/{clientPersona}', ['uses' => 'ClientPersonaController@show', 'as' => 'show', 'roles' => ['adm', 'usr']]);
            Route::get('/{clientPersona}/information', ['uses' => 'ClientPersonaController@information', 'as' => 'information', 'roles' => ['adm', 'usr']]);
            Route::put('/{clientPersona}', ['uses' => 'ClientPersonaController@update', 'as' => 'update', 'roles' => ['adm', 'usr']]);
            Route::delete('/{clientPersona}', ['uses' => 'ClientPersonaController@destroy', 'as' => 'delete', 'roles' => ['adm', 'usr']]);

            // Address
            Route::group(['prefix' => '{clientPersona}/address', 'as' => 'addresses.'], function () {
                Route::get('/', ['uses' => 'ClientPersonaAddressController@index', 'as' => 'index']);
                Route::post('/', ['uses' => 'ClientPersonaAddressController@store', 'as' => 'store', 'roles' => ['adm', 'usr']]);
                Route::delete('/{clientPersonaAddress}', ['uses' => 'ClientPersonaAddressController@destroy', 'as' => 'delete', 'roles' => ['adm', 'usr']]);
                Route::post('/{clientPersonaAddress}/main', ['uses' => 'ClientPersonaAddressController@main', 'as' => 'main', 'roles' => ['adm', 'usr']]);
            });

            // E-mails
            Route::group(['prefix' => '{clientPersona}/emails', 'as' => 'emails.'], function () {
                Route::get('/', ['uses' => 'ClientPersonaEmailController@index', 'as' => 'index']);
                Route::post('/', ['uses' => 'ClientPersonaEmailController@store', 'as' => 'store', 'roles' => ['adm', 'usr']]);
                Route::delete('/{clientPersonaEmail}', ['uses' => 'ClientPersonaEmailController@destroy', 'as' => 'delete', 'roles' => ['adm', 'usr']]);
                Route::post('/{clientPersonaEmail}/main', ['uses' => 'ClientPersonaEmailController@main', 'as' => 'main', 'roles' => ['adm', 'usr']]);
            });

            // Phones
            Route::group(['prefix' => '{clientPersona}/phones', 'as' => 'phones.'], function () {
                Route::get('/', ['uses' => 'ClientPersonaPhoneController@index', 'as' => 'index']);
                Route::post('/', ['uses' => 'ClientPersonaPhoneController@store', 'as' => 'store', 'roles' => ['adm', 'usr']]);
                Route::delete('/{clientPersonaPhone}', ['uses' => 'ClientPersonaPhoneController@destroy', 'as' => 'delete', 'roles' => ['adm', 'usr']]);
                Route::post('/{clientPersonaPhone}/main', ['uses' => 'ClientPersonaPhoneController@main', 'as' => 'main', 'roles' => ['adm', 'usr']]);
            });
        });

        // processos
        Route::group(['prefix' => '{client}/processos', 'as' => 'processes.'], function () {
            Route::get('/{clientProcess}', ['uses' => 'ClientProcessController@index', 'as' => 'index']);
            Route::post('/', ['uses' => 'ClientProcessController@store', 'as' => 'store']);

            // add task
            Route::post('/{clientProcess}/tasks', ['uses' => 'ClientProcessTaskController@store', 'as' => 'tasks.store']);
            Route::put('/{clientProcess}/tasks/{clientProcessTask}/done', ['uses' => 'ClientProcessTaskController@done', 'as' => 'tasks.done']);
            Route::delete('/{clientProcess}/tasks/{clientProcessTask}', ['uses' => 'ClientProcessTaskController@destroy', 'as' => 'tasks.delete']);

            // payment
            Route::post('/{clientProcess}/payments', ['uses' => 'ClientProcessPaymentController@store', 'as' => 'payments.store']);
            Route::delete('/{clientProcess}/payments/{clientProcessPayment}', ['uses' => 'ClientProcessPaymentController@destroy', 'as' => 'payments.delete']);

            // comments
            Route::get('/{clientProcess}/tasks/{clientProcessTask}', ['uses' => 'ClientProcessTaskCommentController@index', 'as' => 'tasks.comments.index']);
            Route::post('/{clientProcess}/tasks/{clientProcessTask}', ['uses' => 'ClientProcessTaskCommentController@store', 'as' => 'tasks.comments.store']);

            // histórico
            Route::get('/{clientProcess}/history', ['uses' => 'ClientProcessController@history', 'as' => 'history']);
        });

        // tarefas
        Route::group(['prefix' => '{client}/tasks', 'as' => 'tasks.'], function () {
            Route::post('/', ['uses' => 'ClientTaskController@store', 'as' => 'store']);
            Route::get('/{clientTask}/finalizar', ['uses' => 'ClientTaskController@done', 'as' => 'done']);

            // Comentários
            Route::group(['prefix' => '{clientTask}/comments', 'as' => 'comments.'], function () {
                Route::get('/', ['uses' => 'ClientTaskCommentController@index', 'as' => 'index']);
                Route::post('/', ['uses' => 'ClientTaskCommentController@store', 'as' => 'store']);
            });
        });

        // assinaturas
        Route::group(['prefix' => '{client}/assinatuas', 'as' => 'subscriptions.'], function () {
            Route::get('/{clientSubscription}/pagamentos', ['uses' => 'ClientSubscriptionController@show', 'as' => 'show', 'roles' => ['adm', 'usr']]);
            Route::post('/', ['uses' => 'ClientSubscriptionController@store', 'as' => 'store', 'roles' => ['adm', 'usr']]);
            Route::get('/{clientSubscription}/close', ['uses' => 'ClientSubscriptionController@close', 'as' => 'close', 'roles' => ['adm', 'usr']]);

            // add payment
            Route::post('/{clientSubscription}/payments/', ['uses' => 'ClientSubscriptionPaymentController@store', 'as' => 'payments.store', 'roles' => ['adm', 'usr']]);
            Route::delete('/{clientSubscription}/payments/{clientSubscriptionPayment}', ['uses' => 'ClientSubscriptionPaymentController@destroy', 'as' => 'payments.delete', 'roles' => ['adm', 'usr']]);

            // subscriptions
            Route::group(['prefix' => '{clientSubscription}/tarefas', 'as' => 'tasks.'], function () {
                Route::get('', ['uses' => 'ClientSubscriptionController@show', 'as' => 'show', 'roles' => ['adm', 'usr']]);
                Route::get('{clientSubscriptionTask}', ['uses' => 'ClientSubscriptionTaskController@done', 'as' => 'done', 'roles' => ['adm', 'usr']]);
                Route::group(['prefix' => '{clientSubscriptionTask}/comments', 'as' => 'comments.'], function () {
                    Route::get('/', ['uses' => 'ClientSubscriptionTaskCommentController@index', 'as' => 'index']);
                    Route::post('/', ['uses' => 'ClientSubscriptionTaskCommentController@store', 'as' => 'store']);
                });
            });
        });

        // mandatos
        Route::group(['prefix' => '{client}/mandatos', 'as' => 'mandatos.'], function () {
            Route::get('', ['uses' => 'ClientMandatoController@index', 'as' => 'index', 'roles' => ['adm', 'usr']]);
            Route::get('adicionar', ['uses' => 'ClientMandatoController@create', 'as' => 'create', 'roles' => ['adm', 'usr']]);
            Route::post('', ['uses' => 'ClientMandatoController@store', 'as' => 'store', 'roles' => ['adm', 'usr']]);
            Route::delete('{clientMandato}', ['uses' => 'ClientMandatoController@destroy', 'as' => 'delete', 'roles' => ['adm', 'usr']]);
        });
    });

    # rota de processos internos
    Route::group(['prefix' => 'processos', 'as' => 'processes.'], function () {
        Route::get('', ['uses' => 'InternalProcessController@index', 'as' => 'index', 'roles' => ['adm', 'usr']]);
        Route::get('/adicionar', ['uses' => 'InternalProcessController@create', 'as' => 'create', 'roles' => ['adm', 'usr']]);
        Route::post('/', ['uses' => 'InternalProcessController@store', 'as' => 'store', 'roles' => ['adm', 'usr']]);
        Route::get('/{internalProcess}/editar', ['uses' => 'InternalProcessController@edit', 'as' => 'edit', 'roles' => ['adm', 'usr']]);
        Route::put('/{internalProcess}', ['uses' => 'InternalProcessController@update', 'as' => 'update', 'roles' => ['adm', 'usr']]);
        Route::delete('/{internalProcess}', ['uses' => 'InternalProcessController@destroy', 'as' => 'delete', 'roles' => ['adm', 'usr']]);

        Route::post('/{internalProcess}/attach-task', ['uses' => 'InternalProcessController@attachTask', 'as' => 'attach.task', 'roles' => ['adm', 'usr']]);
        Route::delete('/{internalProcess}/detach-task', ['uses' => 'InternalProcessController@detachTask', 'as' => 'detach.task', 'roles' => ['adm', 'usr']]);

        Route::put('/{internalProcess}/up', ['uses' => 'InternalProcessController@putUp', 'as' => 'task.up', 'roles' => ['adm', 'usr']]);
        Route::put('/{internalProcess}/down', ['uses' => 'InternalProcessController@putDown', 'as' => 'task.down', 'roles' => ['adm', 'usr']]);
    });

    # rota de tarefas internos
    Route::group(['prefix' => 'tarefas', 'as' => 'tasks.'], function () {
        Route::get('', ['uses' => 'InternalTaskController@index', 'as' => 'index', 'roles' => ['adm', 'usr']]);
        Route::get('/adicionar', ['uses' => 'InternalTaskController@create', 'as' => 'create', 'roles' => ['adm', 'usr']]);
        Route::post('/', ['uses' => 'InternalTaskController@store', 'as' => 'store', 'roles' => ['adm', 'usr']]);
        Route::get('/{internalTask}/editar', ['uses' => 'InternalTaskController@edit', 'as' => 'edit', 'roles' => ['adm', 'usr']]);
        Route::put('/{internalTask}', ['uses' => 'InternalTaskController@update', 'as' => 'update', 'roles' => ['adm', 'usr']]);
        Route::delete('/{internalTask}', ['uses' => 'InternalTaskController@destroy', 'as' => 'delete', 'roles' => ['adm', 'usr']]);
    });

    # rota de cartórios
    Route::group(['prefix' => 'cartorios', 'as' => 'notaryAddresses.'], function () {
        Route::get('', ['uses' => 'NotaryAddressController@index', 'as' => 'index', 'roles' => ['adm', 'usr']]);
        Route::get('/adicionar', ['uses' => 'NotaryAddressController@create', 'as' => 'create', 'roles' => ['adm', 'usr']]);
        Route::post('/', ['uses' => 'NotaryAddressController@store', 'as' => 'store', 'roles' => ['adm', 'usr']]);
        Route::get('/{notaryAddress}/editar', ['uses' => 'NotaryAddressController@edit', 'as' => 'edit', 'roles' => ['adm', 'usr']]);
        Route::put('/{notaryAddress}', ['uses' => 'NotaryAddressController@update', 'as' => 'update', 'roles' => ['adm', 'usr']]);
        Route::delete('/{notaryAddress}', ['uses' => 'NotaryAddressController@destroy', 'as' => 'delete', 'roles' => ['adm', 'usr']]);
    });

    # rota de assinaturas
    Route::group(['prefix' => 'assinaturas', 'as' => 'subscriptions.'], function () {
        Route::get('', ['uses' => 'SubscriptionController@index', 'as' => 'index', 'roles' => ['adm', 'usr']]);
        Route::get('adicionar', ['uses' => 'SubscriptionController@create', 'as' => 'create', 'roles' => ['adm', 'usr']]);
        Route::post('/', ['uses' => 'SubscriptionController@store', 'as' => 'store', 'roles' => ['adm', 'usr']]);
        Route::get('/{subscription}', ['uses' => 'SubscriptionController@edit', 'as' => 'edit', 'roles' => ['adm']]);
        Route::put('/{subscription}', ['uses' => 'SubscriptionController@update', 'as' => 'update', 'roles' => ['adm', 'usr']]);
        Route::delete('/{subscription}', ['uses' => 'SubscriptionController@destroy', 'as' => 'delete', 'roles' => ['adm', 'usr']]);
    });

    # rota de geracao de documentos
    Route::group(['prefix' => 'geracao-de-documentos', 'as' => 'documents.'], function () {
        Route::get('/ata-fundacao', ['uses' => 'GeraDocumentoController@ataFundacaoView', 'as' => 'ataFundacao', 'roles' => ['adm', 'usr']]);
        Route::post('/ata-fundacao/personas', ['uses' => 'GeraDocumentoController@ataFundacaoGetPersonas', 'as' => 'ataFundacao.personas', 'roles' => ['adm', 'usr']]);
        Route::post('/ata-fundacao', ['uses' => 'GeraDocumentoController@ataFundacaoPost', 'roles' => ['adm', 'usr']]);

        Route::match(['get', 'post'], '/estatuto-episcopal', ['uses' => 'GeraDocumentoController@estatutoEpiscopal', 'as' => 'estatutoEpiscopal', 'roles' => ['adm', 'usr']]);
        Route::match(['get', 'post'], '/contrato-contabil', ['uses' => 'GeraDocumentoController@contratoContabil', 'as' => 'contratoContabil', 'roles' => ['adm', 'usr']]);
        Route::match(['get', 'post'], '/edital-convocacao', ['uses' => 'GeraDocumentoController@editalConvocacao', 'as' => 'editalConvocacao', 'roles' => ['adm', 'usr']]);
        Route::match(['get', 'post'], '/contrato-abertura', ['uses' => 'GeraDocumentoController@contratoAbertura', 'as' => 'contratoAbertura', 'roles' => ['adm', 'usr']]);
        Route::match(['get', 'post'], '/estatuto-congregacional', ['uses' => 'GeraDocumentoController@estatudoCongregacional', 'as' => 'estatutoCongregacional', 'roles' => ['adm', 'usr']]);

        Route::group(['prefix' => 'processamento', 'as' => 'generations.'], function () {
            Route::post('/ata-funcao', ['uses' => 'GeraDocumentoController@ataFuncaoDocument', 'as' => 'ataFuncao']);
        });
    });

    # rota de serviços
    Route::group(['prefix' => 'servicos', 'as' => 'services.'], function () {
        Route::get('', ['uses' => 'ServiceController@index', 'as' => 'index', 'roles' => ['adm', 'usr']]);
        Route::post('/', ['uses' => 'ServiceController@store', 'as' => 'store', 'roles' => ['adm', 'usr']]);
        Route::get('/adicionar', ['uses' => 'ServiceController@create', 'as' => 'create', 'roles' => ['adm', 'usr']]);
        Route::get('/{service}', ['uses' => 'ServiceController@edit', 'as' => 'edit', 'roles' => ['adm', 'usr']]);
        Route::put('/{service}', ['uses' => 'ServiceController@update', 'as' => 'update', 'roles' => ['adm', 'usr']]);
        Route::delete('/{service}', ['uses' => 'ServiceController@destroy', 'as' => 'delete', 'roles' => ['adm', 'usr']]);
    });

    # rota mandatos
    Route::get('mandatos', ['uses' => 'ClientMandatoController@all', 'as' => 'mandatos', 'role' => ['adm']]);

    # rota de relatorios
    Route::group(['prefix' => 'relatorios', 'as' => 'relatorios.'], function () {
        Route::get('/processos-abertos', ['uses' => 'RelatorioController@processoAberto', 'as' => 'processoAberto']);
        Route::get('/processos-fechados', ['uses' => 'RelatorioController@processoFechado', 'as' => 'processoFechado']);
        Route::get('/pagamentos-abertos', ['uses' => 'RelatorioController@pagamentoAberto', 'as' => 'pagamentoAberto']);
        Route::get('/tarefas-abertas', ['uses' => 'RelatorioController@tarefaAberta', 'as' => 'tarefaAberta']);
        Route::get('/tarefas-fechadas', ['uses' => 'RelatorioController@tarefaFechada', 'as' => 'tarefaFechada']);
    });


    Route::group(['prefix' => 'api', 'as' => 'api.'], function () {
        Route::group(['prefix' => 'charts', 'as' => 'charts.'], function () {
            Route::get('received', ['uses' => 'ChartController@received', 'as' => 'received']);
        });
        Route::group(['prefix' => 'widgets', 'as' => 'widgets.'], function () {
            Route::get('clients-registred', ['uses' => 'WidgetController@clientsRegistred', 'as' => 'clientsRegistred']);
        });
    });


    Route::post('single-task-delay', ['uses' => 'ClientTaskController@delay', 'as' => 'singleTaskDelay', 'roles' => ['adm', 'usr']]);
    Route::post('process-task-delay', ['uses' => 'ClientProcessTaskController@delay', 'as' => 'processTaskDelay', 'roles' => ['adm', 'usr']]);
    Route::post('subscription-task-delay', ['uses' => 'ClientSubscriptionTaskController@delay', 'as' => 'subscriptionTaskDelay', 'roles' => ['adm', 'usr']]);

    Route::get('assinatura-task-close/{clientSubscriptionTask}', ['uses' => 'ClientSubscriptionTaskController@done', 'as' => 'assinaturaTaskClose']);
    Route::get('assinatura-task-comments/{clientSubscriptionTask}', ['uses' => 'ClientSubscriptionTaskController@comments', 'as' => 'assinaturaTaskComments']);
    Route::post('assinatura-task-comments/{clientSubscriptionTask}', ['uses' => 'ClientSubscriptionTaskController@newComment', 'as' => 'assinaturaTaskNewComment']);

    // LOAD COMMENTS
    Route::group(['prefix' => 'task-comments', 'as' => 'task.comments.'], function () {
        Route::get('single/{task}', ['uses' => 'ClientTaskCommentController@index', 'as' => 'single']);
        Route::get('process/{task}', ['uses' => 'ClientProcessTaskCommentController@index', 'as' => 'process']);
        Route::get('subscription/{task}', ['uses' => 'ClientSubscriptionTaskCommentController@index', 'as' => 'subscription']);
    });

    Route::get('meu-setor', 'InternalTaskController@sector')->name('sectorTasks.index');
});

Route::get("/sair", function () {
    (Auth::check()) ? Auth::logout() : null;
    return redirect()->route('login');
});

Route::get('import', function () {
    $json = json_decode(file_get_contents(public_path('empresa.json')), true);
    $array = [];
    foreach ($json as $item) {
        $d = [
            'name' => $item['nome_empresa'],
            'document' => maskDocument($item['cnpj_cpf_empresa']),
            'email' => $item['email'],
            'phone' => maskPhone($item['telefone']),
            'type' => 'Igreja',
            'zip' => $item['cep'],
            'state' => ($item['uf'] == "NULL") ? null : $item['uf'],
            'city' => $item['cidade'],
            'neighborhood' => $item['bairro'],
            'street' => $item['endereco'],
            'complement' => $item['complemento']
        ];
        array_push($array, $d);

        // include
        if (!\App\Client::where('document', $d['document'])->exists()) {
            \App\Client::create($d);
        }
    }

    return response()->json(['message' => 'Importação concluída'], 200);
});


Route::get('pagamentos', 'PaymentsController@index')->name('app.payments');
Route::post('pagamentos', 'PaymentsController@load')->name('app.payments.load');

Route::group(['prefix' => 'external'], function () {

    //Empresa
    Route::group(['prefix' => 'enterprises'], function () {
        Route::get('/', ['uses' => 'ExternalController@getEnterprises']);
        Route::get('/{id}', ['uses' => 'ExternalController@getEnterpriseById']);
        Route::post('/', ['uses' => 'ExternalController@createEnterprise']);
        Route::put('/{client}', ['uses' => 'ExternalController@updateEnterprise']);
    });

    //Membros
    Route::group(['prefix' => 'members'], function () {
        Route::get('/{id}', ['uses' => 'ExternalController@getMembers']);
        Route::get('/{member}/enterprise/{client}', ['uses' => 'ExternalController@getMemberById']);
        Route::post('/{client}', ['uses' => 'ExternalController@createMember']);
        Route::post('/{member}/enterprise/{enterprise}/phone', ['uses' => 'ExternalController@setMemberPhone']);
        Route::post('/{member}/enterprise/{enterprise}/phone/default', ['uses' => 'ExternalController@setMemberPhoneDefault']);
        Route::post('/{member}/enterprise/{enterprise}/email', ['uses' => 'ExternalController@setMemberEmail']);
        Route::post('/{member}/enterprise/{enterprise}/address', ['uses' => 'ExternalController@setMemberAddress']);
        Route::put('/{member}/enterprise/{client}', ['uses' => 'ExternalController@updateMember']);
    });

    // Mandatos
    Route::group(['prefix' => 'mandatos'], function () {
        Route::get('/{client}', ['uses' => 'ExternalController@getMandatos']);
        Route::post('/{client}', ['uses' => 'ExternalController@createMandato']);
    });

    // Assinaturas
    Route::group(['prefix' => 'subscriptions'], function () {
        Route::get('/', ['uses' => 'ExternalController@getSubscriptions']);
    });

    // Tarefas
    Route::group(['prefix' => 'tasks'], function () {
        Route::get('/', ['uses' => 'ExternalController@getTasks']);
        Route::get('/enterprise/{id}', ['uses' => 'ExternalController@getTaskInEnterprise']);
        Route::post('/', ['uses' => 'ExternalController@createTask']);
        Route::put('/{id}', ['uses' => 'ExternalController@updateTask']);
    });

    // Processos
    Route::group(['prefix' => 'processes'], function () {
        Route::get('/', ['uses' => 'ExternalController@getProcesses']);
        Route::get('/enterprise/{id}', ['uses' => 'ExternalController@getProcessInEnterprise']);
        Route::get('/{id}', ['uses' => 'ExternalController@getProcessById']);
        Route::post('/', ['uses' => 'ExternalController@createProcess']);
        Route::post('/enterprise/{client}', ['uses' => 'ExternalController@setProcessInEnterprise']);
        Route::put('/{internalProcess}', ['uses' => 'ExternalController@updateProcess']);
    });
});
