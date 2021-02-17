import CreateAlert from './views/Alerts/Create'
import EditAlert from './views/Alerts/Edit'
import ListAlerts from './views/Alerts/Index'
import ShowAlert from './views/Alerts/Show'
import Checkout from './views/Checkout/Index'
import CreateCompany from './views/Companies/Create'
import EditCompany from './views/Companies/Edit'
import ListCompanies from './views/Companies/Index'
import ShowCompany from './views/Companies/Show'
import CreateJob from './views/Jobs/Create'
import EditJob from './views/Jobs/Edit'
import ListJobs from './views/Jobs/Index'
import ShowJob from './views/Jobs/Show'
import ListOrders from './views/Orders/Index'
import EditProfile from './views/Profile/Edit'
import AllJobs from './views/AllJobs'

export default [
    {
        path: '/jobs/all',
        name: 'jobs.all',
        component: AllJobs,
    },
    {
        path: '/alerts',
        name: 'alerts.index',
        component: ListAlerts,
        meta: {
            middleware: 'auth'
        }
    },
    {
        path: '/alerts/create',
        name: 'alerts.create',
        component: CreateAlert,
        meta: {
            middleware: 'auth'
        }
    },
    {
        path: '/alerts/:alert',
        name: 'alerts.show',
        component: ShowAlert,
        meta: {
            middleware: 'auth'
        }
    },
    {
        path: '/alerts/:alert/edit',
        name: 'alerts.edit',
        component: EditAlert,
        props: true,
        meta: {
            middleware: 'auth'
        }
    },
    {
        path: '/companies',
        name: 'companies.index',
        component: ListCompanies,
        middleware: 'auth'
    },
    {
        path: '/companies/create',
        name: 'companies.create',
        component: CreateCompany,
        meta: {
            middleware: 'auth'
        }
    },
    {
        path: '/companies/:company',
        name: 'companies.show',
        component: ShowCompany,
        props: true,
        meta: {
            middleware: 'auth'
        }
    },
    {
        path: '/companies/:company/edit',
        name: 'companies.edit',
        component: EditCompany,
        props: true,
        meta: {
            middleware: 'auth'
        }
    },
    {
        path: '/jobs',
        name: 'jobs.index',
        component: ListJobs,
        meta: {
            middleware: 'auth'
        }
    },
    {
        path: '/jobs/create',
        name: 'jobs.create',
        component: CreateJob,
        meta: {
            middleware: 'auth'
        }
    },
    {
        path: '/jobs/:job',
        name: 'jobs.show',
        component: ShowJob,
        props: true,
        meta: {
            middleware: 'auth'
        }
    },
    {
        path: '/jobs/:job/edit',
        name: 'jobs.edit',
        component: EditJob,
        props: true,
        meta: {
            middleware: 'auth'
        }
    },
    {
        path: '/jobs/:jobId/checkout',
        name: 'checkout.index',
        component: Checkout,
        props: true,
        meta: {
            middleware: 'auth'
        }
    },
    {
        path: '/orders',
        name: 'orders.index',
        component: ListOrders,
        meta: {
            middleware: 'auth'
        }
    },
    {
        path: '/user/profile',
        name: 'user.profile',
        component: EditProfile,
        meta: {
            middleware: 'auth'
        }
    }
];
