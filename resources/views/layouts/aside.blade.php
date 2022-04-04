<aside class="main-sidebar dark-bg">
    <section class="sidebar">
        <div class="user-panel black-bg">
            <div class="pull-left image">
                <img src="{!! getAvatar(auth()->user()->id)!!}" class="img-circle appUserAvatar" alt="User Image">
            </div>
            <div class="pull-left info">
                <p class="appUserName">{{auth()->user()->name}}</p>
            </div>
        </div>

        {{-- Sidebar Menu --}}
        <ul class="sidebar-menu tree" data-widget="tree">

            @if(auth()->user()->hasAnyRole(['adm']))
                <li class="header dark-bg">Módulos</li>
                <li>
                    <a href="{!! route('app.processes.index') !!}">
                        <i class="fa fa-clipboard-check"></i> <span>Processos</span>
                    </a>
                </li>
                <li>
                    <a href="{!! route('app.tasks.index') !!}">
                        <i class="fa fa-check-circle"></i> <span>Tarefas</span>
                    </a>
                </li>
                <li>
                    <a href="{!! route('app.services.index') !!}">
                        <i class="fa fa-tags"></i> <span>Serviços</span>
                    </a>
                </li>
                <li>
                    <a href="{!! route('app.subscriptions.index') !!}">
                        <i class="fa fa-file-signature"></i> <span>Assinaturas</span>
                    </a>
                </li>
            @endif


            <li class="header dark-bg">Menu</li>

            <li>
                <a href="{!! route('app.index') !!}">
                    <i class="fa fa-tachometer-alt"></i> <span>Painel</span>
                </a>
            </li>

            @if (auth()->user()->hasAnyRole(['adm']))
                <li>
                    <a href="{!! route('app.users.index') !!}">
                        <i class="fa fa-user-secret"></i> <span>Usuários</span>
                    </a>
                </li>
            @endif

            <li>
                <a href="{!! route('app.clients.index') !!}">
                    <i class="fa fa-users"></i> <span>Clientes</span>
                </a>
            </li>

            <li>
                <a href="{!! route('app.mandatos') !!}">
                    <i class="fa fa-ribbon"></i> <span>Mandatos</span>
                </a>
            </li>

            <li>
                <a href="{!! route('app.notaryAddresses.index') !!}">
                    <i class="fa fa-warehouse"></i> <span>Cartórios</span>
                </a>
            </li>

            <li>
                <a href="{!! route('app.sectorTasks.index') !!}">
                    <i class="fa fa-book-open"></i> <span>Meu Setor</span>
                </a>
            </li>


            @php
                $arrayMenuGeraDocumentos = [
                    'app.documents.ataFundacao',
                    'app.documents.estatutoEspecial',
                    'app.documents.contratoAbertura',
                    'app.documents.contratoContabil'
                    ];
            @endphp
            <li class="treeview {{ menuPath($arrayMenuGeraDocumentos, 'menu-open') }}">
                <a href="javascript:void(0)">
                    <i class="fa fa-copy"></i><span>Geração de Documentos</span>
                    <span class="pull-right-container"> <i class="fa fa-angle-left pull-right"></i> </span>
                </a>
                <ul class="treeview-menu" {{ (menuPath($arrayMenuGeraDocumentos)) ? 'style=display:block' : null }} >
                    <li class="{{ menuPath(['app.documents.ataFundacao'], 'active') }}">
                        <a href="{{ route('app.documents.ataFundacao') }}">Ata de Fundação</a>
                    </li>
                    <li class="{{ menuPath(['app.documents.editalConvocacao'], 'active') }}">
                        <a href="{{ route('app.documents.editalConvocacao') }}">Edital de Convocação</a>
                    </li>
                    <li class="{{ menuPath(['app.documents.estatutoCongregacional'], 'active') }}">
                        <a href="{{ route('app.documents.estatutoCongregacional') }}">Congregacional</a>
                    </li>
                    <li class="{{ menuPath(['app.documents.estatutoEpiscopal'], 'active') }}">
                        <a href="{{ route('app.documents.estatutoEpiscopal') }}">Episcopal</a>
                    </li>
                    <li class="{{ menuPath(['app.documents.contratoAbertura'], 'active') }}">
                        <a href="{{ route('app.documents.contratoAbertura') }}">Contrato Abertura</a>
                    </li>
                    <li class="{{ menuPath(['app.documents.contratoContabil'], 'active') }}">
                        <a href="{{ route('app.documents.contratoContabil') }}">Contrato Contábil</a>
                    </li>
                </ul>
            </li>

            @php
                $arrayMenuRelatorio = [
                    'app.relatorios.processoAberto',
                    'app.relatorios.processoAberto',
                    'app.relatorios.pagamentoAberto',
                    'app.relatorios.tarefaAberta',
                    'app.relatorios.tarefaFechada',
                ];
            @endphp
            <li class="treeview {{ menuPath($arrayMenuRelatorio, 'menu-open') }}">
                <a href="javascript:void(0)">
                    <i class="fa fa-copy"></i><span>Relatórios</span>
                    <span class="pull-right-container"> <i class="fa fa-angle-left pull-right"></i> </span>
                </a>
                <ul class="treeview-menu" {{ (menuPath($arrayMenuRelatorio)) ? 'style=display:block' : null }} >
                    <li class="{{ menuPath(['app.relatorios.processoAberto'], 'active') }}">
                        <a href="{{ route('app.relatorios.processoAberto') }}">Processos Abertos</a>
                    </li>
                    <li class="{{ menuPath(['app.relatorios.processoFechado'], 'active') }}">
                        <a href="{{ route('app.relatorios.processoFechado') }}">Processos Fechados</a>
                    </li>
                    <li class="{{ menuPath(['app.relatorios.tarefaAberta'], 'active') }}">
                        <a href="{{ route('app.relatorios.tarefaAberta') }}">Tarefas Abertas</a>
                    </li>
                    <li class="{{ menuPath(['app.relatorios.tarefaFechada'], 'active') }}">
                        <a href="{{ route('app.relatorios.tarefaFechada') }}">Tarefas Fechadas</a>
                    </li>
                    <li class="{{ menuPath(['app.relatorios.pagamentoAberto'], 'active') }}">
                        <a href="{{ route('app.relatorios.pagamentoAberto') }}">Pagamentos Abertos</a>
                    </li>

                </ul>
            </li>

            <li>
                <a href="{!! route('app.payments') !!}">
                    <i class="fa fa-hand-holding-usd"></i> Pagamentos
                </a>
            </li>
        </ul>

    </section>
</aside>
