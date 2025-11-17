import { index as menuCategoriesIndex, update as menuCategoriesUpdate } from '@/actions/App/Http/Controllers/MenuCategoryController';
import Heading from '@/components/heading';
import InputError from '@/components/input-error';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import AppLayout from '@/layouts/app-layout';
import { dashboard } from '@/routes';
import { type BreadcrumbItem } from '@/types';
import { Form, Head } from '@inertiajs/react';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: dashboard().url,
    },
    {
        title: 'Menu Categories',
        href: menuCategoriesIndex().url,
    },
    {
        title: 'Edit',
        href: '#',
    },
];

interface MenuCategory {
    id: number;
    name: string;
    slug: string;
}

interface Props {
    category: MenuCategory;
}

export default function Edit({ category }: Props) {
    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Edit Menu Category" />

            <div className="flex h-full flex-1 flex-col gap-4 overflow-x-auto p-4">
                <Heading title="Edit Menu Category" />

                <div className="max-w-2xl rounded-xl border border-sidebar-border/70 p-6 dark:border-sidebar-border">
                    <Form action={menuCategoriesUpdate(category.id).url} method="put" className="space-y-6">
                        {({ processing, errors }) => (
                            <>
                                <div className="grid gap-2">
                                    <Label htmlFor="name">Name</Label>
                                    <Input
                                        id="name"
                                        name="name"
                                        defaultValue={category.name}
                                        required
                                        placeholder="Category name"
                                    />
                                    <InputError message={errors.name} />
                                </div>

                                <div className="grid gap-2">
                                    <Label htmlFor="slug">Slug</Label>
                                    <Input
                                        id="slug"
                                        name="slug"
                                        defaultValue={category.slug}
                                        required
                                        placeholder="category-slug"
                                    />
                                    <InputError message={errors.slug} />
                                </div>

                                <div className="flex gap-2">
                                    <Button type="submit" disabled={processing}>
                                        {processing ? 'Updating...' : 'Update Category'}
                                    </Button>
                                    <Button
                                        type="button"
                                        variant="outline"
                                        onClick={() => window.history.back()}
                                    >
                                        Cancel
                                    </Button>
                                </div>
                            </>
                        )}
                    </Form>
                </div>
            </div>
        </AppLayout>
    );
}
