import { Head } from '@inertiajs/react';

import { type BreadcrumbItem } from '@/types';

import AppLayout from '@/layouts/app-layout';
import SettingsLayout from '@/layouts/settings/layout';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Appearance settings',
        href: '/settings/appearance',
    },
];

export default function Appearance() {
    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Appearance settings" />

            <SettingsLayout>
                <div className="space-y-6">
                    <h2>Appearance settings</h2>

                    <p className="fs-6 fst-italic">
                        Update your account's appearance settings
                    </p>
                    {/* <AppearanceTabs /> */}
                </div>
            </SettingsLayout>
        </AppLayout>
    );
}
