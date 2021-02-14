import CreateAlert from './views/Alerts/Create'
import EditAlert from './views/Alerts/Edit'
import ListAlerts from './views/Alerts/Index'
import ShowAlert from './views/Alerts/Show'
import CreateCompany from './views/Companies/Create'
import EditCompany from './views/Companies/Edit'
import ListCompanies from './views/Companies/Index'
import ShowCompany from './views/Companies/Show'
import CreateJob from './views/Jobs/Create'
import EditJob from './views/Jobs/Edit'
import ListJobs from './views/Jobs/Index'
import ShowJob from './views/Jobs/Show'
import ListOrders from './views/Orders/Index'
import ShowOrder from './views/Orders/Show'
import Profile from './views/Profile/Show'
import Dashboard from './views/Dashboard'
import AllJobs from './views/AllJobs'

export default [
    {
        path: '/dashboard',
        name: 'dashboard',
        component: Dashboard
    },
    {
        path: '/jobs/all',
        name: 'jobs.all',
        component: AllJobs
    },
    {
        path: '/alerts',
        name: 'alerts.index',
        component: ListAlerts
    },
    {
        path: '/alerts/create',
        name: 'alerts.create',
        component: CreateAlert
    },
    {
        path: '/alerts/:id',
        name: 'alerts.show',
        component: ShowAlert
    },
    {
        path: '/alerts/:id/edit',
        name: 'alerts.edit',
        component: EditAlert
    },
    {
        path: '/companies',
        name: 'companies.index',
        component: ListCompanies
    },
    {
        path: '/companies/create',
        name: 'companies.create',
        component: CreateCompany
    },
    {
        path: '/companies/:id',
        name: 'companies.show',
        component: ShowCompany
    },
    {
        path: '/companies/:id/edit',
        name: 'companies.edit',
        component: EditCompany
    },
    {
        path: '/jobs',
        name: 'jobs.index',
        component: ListJobs
    },
    {
        path: '/jobs/create',
        name: 'jobs.create',
        component: CreateJob
    },
    {
        path: '/jobs/:id',
        name: 'jobs.show',
        component: ShowJob
    },
    {
        path: '/jobs/:id/edit',
        name: 'jobs.edit',
        component: EditJob
    },
    {
        path: '/orders',
        name: 'orders.index',
        component: ListOrders,
    },
    {
        path: '/orders/:id',
        name: 'orders.show',
        component: ShowOrder
    },
    {
        path: '/user/profile',
        name: 'user.profile',
        component: Profile
    },
];
