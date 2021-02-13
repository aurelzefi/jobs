import AlertsCreate from './views/Alerts/Create';
import AlertsEdit from './views/Alerts/Edit';
import AlertsIndex from './views/Alerts/Index';
import AlertsShow from './views/Alerts/Show';
import CompaniesCreate from './views/Companies/Create';
import CompaniesEdit from './views/Companies/Edit';
import CompaniesIndex from './views/Companies/Index';
import CompaniesShow from './views/Companies/Show';
import JobsCreate from './views/Jobs/Create';
import JobsEdit from './views/Jobs/Edit';
import JobsIndex from './views/Jobs/Index';
import JobsShow from './views/Jobs/Show';
import OrdersIndex from './views/Orders/Index';
import OrdersShow from './views/Orders/Show';
import Dashboard from './views/Dashboard';
import AllJobs from './views/AllJobs';

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
        component: AlertsIndex
    },
    {
        path: '/alerts/create',
        name: 'alerts.create',
        component: AlertsCreate
    },
    {
        path: '/alerts/:id',
        name: 'alerts.show',
        component: AlertsShow
    },
    {
        path: '/alerts/:id/edit',
        name: 'alerts.edit',
        component: AlertsEdit
    },
    {
        path: '/companies',
        name: 'companies.index',
        component: CompaniesIndex
    },
    {
        path: '/companies/create',
        name: 'companies.create',
        component: CompaniesCreate
    },
    {
        path: '/companies/:id',
        name: 'companies.show',
        component: CompaniesShow
    },
    {
        path: '/companies/:id/edit',
        name: 'companies.edit',
        component: CompaniesEdit
    },
    {
        path: '/jobs',
        name: 'jobs.index',
        component: JobsIndex
    },
    {
        path: '/jobs/create',
        name: 'jobs.create',
        component: JobsCreate
    },
    {
        path: '/jobs/:id',
        name: 'jobs.show',
        component: JobsShow
    },
    {
        path: '/jobs/:id/edit',
        name: 'jobs.edit',
        component: JobsEdit
    },
    {
        path: '/orders',
        name: 'orders.index',
        component: OrdersIndex,
    },
    {
        path: '/orders/:id',
        name: 'orders.show',
        component: OrdersShow
    },
];
