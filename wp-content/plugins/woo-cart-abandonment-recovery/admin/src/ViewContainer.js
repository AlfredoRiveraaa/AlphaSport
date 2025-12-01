import { BrowserRouter } from 'react-router-dom';

import Header from '@Components/common/Header';
import Routes from '@Admin/Routes';
import ProUpgradeHeaderBanner from '@Components/pro/ProUpgradeHeaderBanner';
import HeaderSaleNotice from '@Components/pro/HeaderSaleNotice';

const ViewContainer = () => {
	const isPro = cart_abandonment_admin?.is_pro;
	const hasLicense =
		cart_abandonment_admin?.license_status === '1' ? true : false;
	return (
		<BrowserRouter>
			{ ! ( isPro && hasLicense ) && <HeaderSaleNotice /> }
			<ProUpgradeHeaderBanner />
			<Header />
			<Routes />
		</BrowserRouter>
	);
};

export default ViewContainer;

