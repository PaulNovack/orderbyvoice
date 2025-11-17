import { index as menuSizesIndex, store as menuSizesStore } from '@/actions/App/Http/Controllers/MenuSizeController';
import Heading from '@/components/heading';
import InputError from '@/components/input-error';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
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
        title: 'Menu Sizes',
        href: menuSizesIndex().url,
    },
    {
        title: 'Create',
        href: '#',
    },
];

interface MenuCategory {
    id: number;
    name: string;
}

interface Props {
    categories: MenuCategory[];
}

export default function Create({ categories }: Props) {
    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Create Menu Size" />

            <div className="flex h-full flex-1 flex-col gap-4 overflow-x-auto p-4">
                <Heading title="Create Menu Size" />

                <div className="max-w-2xl rounded-xl border border-sidebar-border/70 p-6 dark:border-sidebar-border">
                    <Form action={menuSizesStore().url} method="post" className="space-y-6">
                        {({ processing, errors }) => (
                            <>
                                <div className="grid gap-2">
                                    <Label htmlFor="category_id">Category</Label>
                                    <Select name="category_id">
                                        <SelectTrigger>
                                            <SelectValue placeholder="Select a category (optional)" />
                                        </SelectTrigger>
                                        <SelectContent>
                                            <SelectItem value="none">None (applies to all)</SelectItem>
                                            {categories.map((category) => (
                                                <SelectItem key={category.id} value={String(category.id)}>
                                                    {category.name}
                                                </SelectItem>
                                            ))}
                                        </SelectContent>
                                    </Select>
                                    <p className="text-xs text-muted-foreground">
                                        Link this size to a specific category (e.g., Pizza sizes for Pizza, Drink sizes for Beverages)
                                    </p>
                                    <InputError message={errors.category_id} />
                                </div>

                                <div className="grid gap-2">
                                    <Label htmlFor="name">Name</Label>
                                    <Input
                                        id="name"
                                        name="name"
                                        required
                                        placeholder="e.g., Extra-Small, Small, Medium, Large"
                                    />
                                    <InputError message={errors.name} />
                                </div>

                                <div className="grid gap-2">
                                    <Label htmlFor="size_note">Size Note (Optional)</Label>
                                    <Input
                                        id="size_note"
                                        name="size_note"
                                        placeholder="e.g., 8 oz, 12 oz, 1/3 lb"
                                    />
                                    <InputError message={errors.size_note} />
                                </div>

                                <div className="grid gap-2">
                                    <Label htmlFor="sort_order">Sort Order</Label>
                                    <Input
                                        id="sort_order"
                                        name="sort_order"
                                        type="number"
                                        defaultValue="0"
                                        min="0"
                                        placeholder="0"
                                    />
                                    <InputError message={errors.sort_order} />
                                </div>

                                <div className="flex gap-2">
                                    <Button type="submit" disabled={processing}>
                                        {processing ? 'Creating...' : 'Create Size'}
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
