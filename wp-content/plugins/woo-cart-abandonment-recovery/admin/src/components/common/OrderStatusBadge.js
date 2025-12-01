import { Badge } from '@bsf/force-ui';

const OrderStatusBadge = ( { status } ) => {
	const statusStyles = {
		Abandoned: 'yellow',
		Failed: 'red',
		Successful: 'green',
	};
	return (
		<Badge
			label={ status }
			size="sm"
			type="pill"
			variant={ statusStyles[ status ] }
			className="w-fit"
		/>
	);
};

export default OrderStatusBadge;
