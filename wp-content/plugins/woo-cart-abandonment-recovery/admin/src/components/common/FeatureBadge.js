import { Badge } from '@bsf/force-ui';

const FeatureBadge = ( { feature = 'NEW' } ) => {
	return (
		<Badge
			label={ feature }
			size="sm"
			type="pill"
			variant="neutral"
			className="w-fit bg-primary-25 text-primary-600 border-flamingo-400 hover:bg-flamingo-50"
		/>
	);
};

export default FeatureBadge;
