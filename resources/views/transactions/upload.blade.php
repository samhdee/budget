import React, { ReactNode, useEffect, useState } from "react";
import Button from 'react-bootstrap/Button';
import Form from 'react-bootstrap/Form';
import Alert from 'react-bootstrap/Alert';
import FloatingLabel from 'react-bootstrap/FloatingLabel';
import { useForm } from '@inertiajs/react';

import Layout from '../includes/layout';
import { useTypedPage } from "@/hooks/use-typed-page";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import { faUpload } from "@fortawesome/free-solid-svg-icons";

const UploadTransacForm = () => {
    const { data, setData, post, progress } = useForm({ file: null });
    const { errors } = useTypedPage().props;

    // Sends new information and gives new values to list
    function handleSubmit(e: React.FormEvent<HTMLFormElement>) {
        e.preventDefault();
        post(route('transac_upload'));
    }

    return (
        <>
            <div className="w-50 mt-5 mx-auto">
                <Form noValidate onSubmit={handleSubmit}>
                    <Form.Group controlId="transac-name">
                        <FloatingLabel label="Montant" className="mb-3">
                            <Form.Control
                                type="file"
                                name="file"
                                onChange={e => setData('file', e.target.files[0])}
                                required
                            />

                            {errors.file &&
                                <Alert
                                    variant="danger"
                                    className="mt-1 py-1 px-2 fs-6 fst-italic"
                                >
                                    {errors.file}
                                </Alert>
                            }

                            <Form.Control.Feedback type="invalid">
                                Eh oh le fichier là
                            </Form.Control.Feedback>

                            {progress && (
                                <progress value={progress.percentage} max="100">
                                    {progress.percentage}%
                                </progress>
                            )}
                        </FloatingLabel>
                    </Form.Group>

                    <Button type="submit" className="me-2" variant="success">
                        <FontAwesomeIcon icon={faUpload} />
                    </Button>
                </Form>
            </div>
        </>
    )
}

UploadTransacForm.layout = (page: ReactNode) => <Layout children={page} title="Chargeeeeeeeez" />

export default UploadTransacForm;