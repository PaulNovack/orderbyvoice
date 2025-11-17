import { index as menuItemsIndex, store as menuItemsStore } from '@/actions/App/Http/Controllers/MenuItemController';
import Heading from '@/components/heading';
import InputError from '@/components/input-error';
import { Button } from '@/components/ui/button';
import { Checkbox } from '@/components/ui/checkbox';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import AppLayout from '@/layouts/app-layout';
import { dashboard } from '@/routes';
import { type BreadcrumbItem } from '@/types';
import { Form, Head } from '@inertiajs/react';
import { useState } from 'react';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: dashboard().url,
    },
    {
        title: 'Menu Items',
        href: menuItemsIndex().url,
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

interface MenuSize {
    id: number;
    name: string;
    size_note: string | null;
    sort_order: number;
}

interface Props {
    categories: MenuCategory[];
    sizesByCategory: Record<string, MenuSize[]>;
}

export default function Create({ categories, sizesByCategory }: Props) {
    const [selectedCategoryId, setSelectedCategoryId] = useState<string | null>(null);
    const availableSizes = selectedCategoryId ? (sizesByCategory[selectedCategoryId] || []) : [];

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Create Menu Item" />

            <div className="flex h-full flex-1 flex-col gap-4 overflow-x-auto p-4">
                <Heading title="Create Menu Item" />

                <div className="max-w-2xl rounded-xl border border-sidebar-border/70 p-6 dark:border-sidebar-border">
                    <Form action={menuItemsStore().url} method="post" className="space-y-6">
                        {({ processing, errors }) => (
                            <>
                                <div className="grid gap-2">
                                    <Label htmlFor="category_id">Category</Label>
                                    <Select 
                                        name="category_id" 
                                        required
                                        onValueChange={(value) => setSelectedCategoryId(value)}
                                    >
                                        <SelectTrigger>
                                            <SelectValue placeholder="Select a category" />
                                        </SelectTrigger>
                                        <SelectContent>
                                            {categories.map((category) => (
                                                <SelectItem key={category.id} value={String(category.id)}>
                                                    {category.name}
                                                </SelectItem>
                                            ))}
                                        </SelectContent>
                                    </Select>
                                    <InputError message={errors.category_id} />
                                </div>

                                <div className="grid gap-2">
                                    <Label htmlFor="name">Name</Label>
                                    <Input
                                        id="name"
                                        name="name"
                                        required
                                        placeholder="Menu item name"
                                    />
                                    <InputError message={errors.name} />
                                </div>

                                <div className="grid gap-2">
                                    <Label htmlFor="description">Description</Label>
                                    <Input
                                        id="description"
                                        name="description"
                                        placeholder="Optional description"
                                    />
                                    <InputError message={errors.description} />
                                </div>

                                <div className="flex items-center gap-2">
                                    <Checkbox id="is_active" name="is_active" defaultChecked />
                                    <Label htmlFor="is_active" className="cursor-pointer">
                                        Active
                                    </Label>
                                </div>

                                {/* Pricing Section */}
                                <div className="space-y-4 rounded-lg border border-sidebar-border/50 p-4 dark:border-sidebar-border">
                                    <div>
                                        <h3 className="text-lg font-semibold">Pricing</h3>
                                        <p className="text-sm text-muted-foreground">
                                            Set prices for each size, or leave blank if not applicable. 
                                            For items without sizes (like sides), use "No Size" option.
                                        </p>
                                    </div>

                                    {/* No Size Option */}
                                    <div className="grid gap-2">
                                        <Label htmlFor="price_no_size">
                                            No Size (Single Price)
                                        </Label>
                                        <Input
                                            id="price_no_size"
                                            name="prices[no_size]"
                                            type="number"
                                            step="0.01"
                                            min="0"
                                            placeholder="0.00"
                                        />
                                        <InputError message={errors['prices.no_size']} />
                                    </div>

                                    {/* Size-based pricing */}
                                    {availableSizes.length > 0 && (
                                        <div className="space-y-3">
                                            <div className="text-sm font-medium">Or set prices by size:</div>
                                            {availableSizes.map((size) => (
                                                <div key={size.id} className="grid gap-2">
                                                    <Label htmlFor={`price_${size.id}`}>
                                                        {size.name}
                                                        {size.size_note && (
                                                            <span className="ml-1 text-xs text-muted-foreground">
                                                                ({size.size_note})
                                                            </span>
                                                        )}
                                                    </Label>
                                                    <Input
                                                        id={`price_${size.id}`}
                                                        name={`prices[${size.id}]`}
                                                        type="number"
                                                        step="0.01"
                                                        min="0"
                                                        placeholder="0.00"
                                                    />
                                                    <InputError message={errors[`prices.${size.id}`]} />
                                                </div>
                                            ))}
                                        </div>
                                    )}
                                </div>

                                <div className="flex gap-2">
                                    <Button type="submit" disabled={processing}>
                                        {processing ? 'Creating...' : 'Create Menu Item'}
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
