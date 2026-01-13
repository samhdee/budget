import { type BreadcrumbItem, type SharedData } from '@/types';
import { Head, Link, useForm, usePage } from '@inertiajs/react';
import { FormEventHandler } from 'react';

import AppLayout from '@/layouts/app-layout';
import SettingsLayout from '@/layouts/settings/layout';
import Form from 'react-bootstrap/esm/Form';
import Button from 'react-bootstrap/esm/Button';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Profile settings',
        href: '/settings/profile',
    },
];

type ProfileForm = {
    name: string;
    email: string;
};

export default function Profile({ mustVerifyEmail, status }: { mustVerifyEmail: boolean; status?: string }) {
    const { auth } = usePage<SharedData>().props;

    const { data, setData, patch, errors, processing, recentlySuccessful } = useForm<Required<ProfileForm>>({
        name: auth.user.name,
        email: auth.user.email,
    });

    const submit: FormEventHandler = (e) => {
        e.preventDefault();

        patch(route('profile.update'), {
            preserveScroll: true,
        });
    };

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Profile settings" />

            <SettingsLayout>
                <div className="space-y-6">
                    <h2>Profil</h2>

                    <Form onSubmit={submit} className="space-y-6">
                        <div className="grid gap-2">
                            <Form.Label htmlFor="name">Name</Form.Label>

                            <Form.Control
                                id="name"
                                className="mt-1"
                                value={data.name}
                                onChange={(e) => setData('name', e.target.value)}
                                required
                                autoComplete="name"
                                placeholder="Full name"
                            />

                            <Form.Control.Feedback type="invalid">{errors.name}</Form.Control.Feedback>
                        </div>

                        <div className="grid gap-2">
                            <Form.Label htmlFor="email">Email address</Form.Label>

                            <Form.Control
                                id="email"
                                type="email"
                                className="mt-1 block w-full"
                                value={data.email}
                                onChange={(e) => setData('email', e.target.value)}
                                required
                                autoComplete="username"
                                placeholder="Email address"
                            />

                            <Form.Control.Feedback type="invalid">{errors.email}</Form.Control.Feedback>
                        </div>

                        {mustVerifyEmail && auth.user.email_verified_at === null && (
                            <div>
                                <p className="-mt-4 text-sm text-muted-foreground">
                                    Your email address is unverified.{' '}
                                    <Button
                                        href={route('verification.send')}
                                        className="btn btn-secondary"
                                    >
                                        Click here to resend the verification email.
                                    </Button>
                                </p>

                                {status === 'verification-link-sent' && (
                                    <div className="mt-2 fs-6 text-success">
                                        A new verification link has been sent to your email address.
                                    </div>
                                )}
                            </div>
                        )}

                        <div className="d-flex align-items-center gap-4">
                            <Button disabled={processing}>Save</Button>

                            {/* <Transition
                                show={recentlySuccessful}
                                enter="transition ease-in-out"
                                enterFrom="opacity-0"
                                leave="transition ease-in-out"
                                leaveTo="opacity-0"
                            >
                                <p className="text-sm text-neutral-600">Saved</p>
                            </Transition> */}
                        </div>
                    </Form>
                </div>
            </SettingsLayout>
        </AppLayout>
    );
}
