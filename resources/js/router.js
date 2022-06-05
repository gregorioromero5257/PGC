import VueRouter from 'vue-router';

import Index from './components/site/Index.vue';
import FeaturedProject from './components/site/Project.vue';
import AboutUs from './components/site/Aboutus';
import WhatWeDo from './components/site/WhatWeDo';
import Vacancies from './components/site/Vacancies';
import Vacancy from './components/site/Vacancy';
import PrivacyPolicy from './components/site/PrivacyPolicy';
import Home from './components/views/Home.vue';
import ManageVacancies from './components/views/Vacancies';
import AddVacancy from './components/views/AddVacancy';
import ManageVacancy from './components/views/Vacancy';
import Staff from './components/views/StaffNew.vue';
import AddStaffMember from './components/views/AddStaffMember.vue';
import Projects from './components/views/Projects.vue';
import Project from './components/views/Project.vue';
import ProjectNew from './components/views/ProjectNew.vue';
import ProjectsNew from './components/views/ProjectsNew.vue';
import AddProject from './components/views/AddProject.vue';
// import Resources from './components/views/Resources.vue';
import Resources from './components/views/ResourcesNew.vue';
import Folder from './components/views/Folder.vue';
import Manage from './components/views/Manage.vue';
import ManageHome from './components/manage/Home.vue';
import PrivacyOptions from './components/manage/projects/PrivacyOptions';
import Locations from './components/manage/projects/Locations.vue';
import Companies from './components/manage/projects/Companies.vue';
import Contracts from './components/manage/projects/Contracts.vue';
import SatisfactionDocuments from './components/manage/projects/SatisfactionDocuments.vue';
import Currencies from './components/manage/projects/Currencies.vue';
import Types from './components/manage/projects/Types.vue';
import Topics from './components/manage/projects/Topics.vue';
import Methodologies from './components/manage/projects/Methodologies.vue';
import Stackeholders from './components/manage/stackeholders/Stakeholders.vue';
import Roles from './components/manage/staff/Roles.vue';
import Positions from './components/manage/staff/Positions.vue';
import Profile from './components/views/Profile.vue';
import EmptyRouterView from './components/EmptyRouterView.vue';
import Codigos from './components/manage/codigos/Codigos.vue';
import Idea from './components/manage/idea/Idea.vue';

let routes = [
    { path: '/', meta: { title: 'C230 Consultores' }, component: Index },
    { path: '/proyectos/:id', meta: { title: 'C230 Consultores | Proyecto destacado' }, component: FeaturedProject },
    { path: '/quienes-somos', meta: { title: 'C230 Consultores | ¿Quiénes somos?' }, component: AboutUs },
    { path: '/que-hacemos', meta: { title: 'C230 Consultores | ¿Qué hacemos?' }, component: WhatWeDo },
    { path: '/vacantes', meta: { title: 'C230 Consultores | Vacantes' }, component: Vacancies },
    { path: '/vacantes/:slug', meta: { title: 'C230 Consultores | Vacante' }, component: Vacancy },
    { path: '/aviso-de-privacidad', meta: { title: 'C230 Consultores | Política de Privacidad' }, component: PrivacyPolicy },
    { path: '/login', meta: { title: 'Iniciar sesión - PGC2.0' } },
    { path: '/home', meta: { title: 'PGC2.0' }, component: Home },
    { path: '/vacancies', meta: { title: 'PGC2.0 | Administrar Vacantes' }, component: ManageVacancies },
    { path: '/vacancies/create', meta: { title: 'PGC2.0 | Agregar Vacante' }, component: AddVacancy },
    { name: 'vacancies', path: '/vacancies/:id', meta: { title: 'PGC2.0 | Vacante' }, component: ManageVacancy, props: true },
    // { name: 'project', path: '/projects/:id', meta: { title: 'Proyecto - PGC2.0' }, component: Project, props: true },

    { path: '/vacancies/:slug', meta: { title: 'PGC2.0 | Vacante' }, component: ManageVacancy },
    { path: '/vacancies/edit/:id', meta: { title: 'Editar Vacante - PGC2.0' }, component: AddVacancy },
    { path: '/vacancies/edit/:slug', meta: { title: 'Editar Vacante - PGC2.0' }, component: AddVacancy },
    { path: '/staff', meta: { title: 'Personal - PGC2.0' }, component: Staff },
    { path: '/staff/create', meta: { title: 'Agregar colaborador - PGC2.0' }, component: AddStaffMember },
    { path: '/staff/:id', meta: { title: 'Información de colaborador - PGC2.0' }, component: Profile },
    { path: '/staff/edit/:id', meta: { title: 'Editar información de colaborador - PGC2.0' }, component: AddStaffMember },
    // { path: '/projects', meta: { title: 'Proyectos - PGC2.0' }, component: Projects },
    { path: '/projects', meta: { title: 'Proyectos - PGC2.0' }, component: ProjectsNew },
    { name: 'create-project', path: '/projects/create', meta: { title: 'Agregar proyecto - PGC2.0' }, component: AddProject, props: true },
    { name: 'edit-project', path: '/projects/edit/:id', meta: { title: 'Editar proyecto - PGC2.0' }, component: AddProject, props: true },
    { name: 'project', path: '/projects/:id', meta: { title: 'Proyecto - PGC2.0' }, component: Project, props: true },
    { path: '/projects/new', meta: { title: 'Proyecto - PGC2.0' }, component: ProjectNew },
    { path: '/resources', meta: { title: 'Recursos - PGC2.0' }, component: Resources },
    { name: 'folder', path: '/resources/folder/:id', meta: { title: 'Folder - PGC2.0' }, component: Folder },
    {
        path: '/manage', meta: { title: 'Administración PGC2.0' }, component: Manage,
        children: [
            { path: 'home', meta: { title: 'Administración PGC2.0' }, component: ManageHome },
            {
                path: 'projects', component: EmptyRouterView,
                children: [
                    { path: 'privacy_options', meta: { title: 'Opciones de privacidad - Proyectos - Administración PGC2.0' }, component: PrivacyOptions },
                    { path: 'locations', meta: { title: 'Ubicaciones geográficas - Proyectos - Administración PGC2.0' }, component: Locations },
                    { path: 'companies', meta: { title: 'Empresas y oficinas - Proyectos - Administración PGC2.0' }, component: Companies },
                    { path: 'contracts', meta: { title: 'Tipos de contrato - Proyectos - Administración PGC2.0' }, component: Contracts },
                    { path: 'satisfaction_documents', meta: { title: 'Tipos de documentos de satisfación - Proyectos - Administración PGC2.0' }, component: SatisfactionDocuments },
                    { path: 'currencies', meta: { title: 'Monedas - Proyectos - Administración PGC2.0' }, component: Currencies },
                    { path: 'types', meta: { title: 'Tipos de proyecto - Proyectos - Administración PGC2.0' }, component: Types },
                    { path: 'methodologies', meta: { title: 'Análisis, metodologías e implementación - Proyectos - Administración PGC2.0' }, component: Methodologies },
                    { path: 'topics', meta: { title: 'Temáticas / Enfoque / Población objetivo - Proyectos - Administración PGC2.0' }, component: Topics },
                ]
            },
            {
                path: 'stackeholders', meta: { title: 'Partes interesadas - Administración PGC2.0' }, component: Stackeholders
            },
            {
                path: 'staff', component: EmptyRouterView,
                children: [
                    { path: 'roles', meta: { title: 'Roles - Personal - Administración PGC2.0' }, component: Roles },
                    { path: 'positions', meta: { title: 'Posiciones - Personal - Administración PGC2.0' }, component: Positions },
                ]
            },
            {
                path: 'codigos', meta: { title: 'Códigos Activos - Administración PGC2.0' }, component: Codigos
            },
            {
                path: 'idea', meta: { title: 'Publicación Web IDEA - Administración PGC2.0' }, component: Idea
            },
        ]
    },
    { path: '/profile', meta: { title: 'Perfil - PGC2.0' }, component: Profile },
]

const router = new VueRouter({
    mode: 'history',
    routes,
    scrollBehavior(to, from, savedPosition) {
        return { x: 0, y: 0 }
    }
})

export default router
