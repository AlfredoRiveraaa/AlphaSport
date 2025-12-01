import { useEffect } from 'react';
import { useLocation } from 'react-router-dom';

import { Toaster } from '@bsf/force-ui';

import Dashboard from '@Admin/pages/Dashboard';
import Settings from '@Admin/pages/Settings';
import FollowUp from '@Admin/pages/FollowUp';
import Product from '@Admin/pages/Product';
import DetailedReport from '@Admin/pages/DetailedReport';
import FollowUpEmails from '@Admin/pages/FollowUpEmails';
import Integrations from '@Admin/pages/Integrations';
// import Ottokit from '@Admin/components/integrations/Ottokit';

const Routes = () => {
	const urlParams = new URLSearchParams( useLocation().search );
	const currentPath = urlParams.get( 'path' ) || 'dashboard';

	useEffect( () => {
		window.scrollTo( { top: 0, behavior: 'smooth' } );
	}, [ currentPath ] );

	// Define routes array with path and component mapping
	const routes = [
		{
			path: 'dashboard',
			component: <Dashboard />,
		},
		{
			path: 'settings',
			component: <Settings />,
		},
		{
			path: 'follow-up',
			component: <FollowUp />,
		},
		{
			path: 'product',
			component: <Product />,
		},
		{
			path: 'detailed-report',
			component: <DetailedReport />,
		},
		{
			path: 'follow-up-emails',
			component: <FollowUpEmails />,
		},
		{
			path: 'integrations',
			component: <Integrations />,
		},
	];

	// Find matching route or default to dashboard
	const routePage = routes.find( ( route ) => route.path === currentPath )
		?.component || <Dashboard />;

	return (
		<div
			className={ `ca-page-content--wrapper ${ currentPath } bg-primary-background` }
		>
			{ routePage }
			<Toaster
				position="top-right"
				design="stack"
				theme="light"
				autoDismiss={ true }
				dismissAfter={ 3000 }
				className="z-999999"
			/>
		</div>
	);
};

export default Routes;
