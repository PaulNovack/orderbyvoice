import { index as menuItemsIndex, update as menuItemsUpdate } from '@/actions/App/Http/Controllers/MenuItemController';
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
        title: 'Edit',
        href: '#',
    },
];

interface MenuCategory {
    id: number;
    name: string;
}

interface ItemPrice {
    id: number;
    size_id: number | null;
    base_price: string;
}

interface MenuSize {
    id: number;
    name: string;
    size_note: string | null;
    sort_order: number;
}

interface Addon {
    id: number;
    name: string;
    type: string;
}

interface AddonGroup {
    id: number;
    name: string;
    addons: Addon[];
}

interface DefaultAddon {
    id: number;
    name: string;
}

interface MenuItem {
    id: number;
    name: string;
    description: string | null;
    is_active: boolean;
    category_id: number;
    category: {
        id: number;
        name: string;
    };
    prices: ItemPrice[];
    default_addons: DefaultAddon[];
}

interface Props {
    item: MenuItem;
    categories: MenuCategory[];
    sizes: MenuSize[];
    addonGroups: AddonGroup[];
}

export default function Edit({ item, categories, sizes, addonGroups }: Props) {
    // Create a map of existing prices for easy lookup
    const existingPrices = item.prices.reduce((acc, price) => {
        const key = price.size_id === null ? 'no_size' : String(price.size_id);
        acc[key] = price.base_price;
        return acc;
    }, {} as Record<string, string>);

    // Create a set of default addon IDs for easy lookup
    const defaultAddonIds = new Set(item.default_addons.map(a => a.id));

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Edit Menu Item" />

            <div className="flex h-full flex-1 flex-col gap-4 overflow-x-auto p-4">
                <Heading title="Edit Menu Item" />

                <div className="max-w-2xl rounded-xl border border-sidebar-border/70 p-6 dark:border-sidebar-border">
                    <Form action={menuItemsUpdate(item.id).url} method="put" className="space-y-6">
                        {({ processing, errors }) => (
                            <>
                                <div className="grid gap-2">
                                    <Label htmlFor="category_id">Category</Label>
                                    <Select name="category_id" defaultValue={String(item.category_id)} required>
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
                                        defaultValue={item.name}
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
                                        defaultValue={item.description || ''}
                                        placeholder="Optional description"
                                    />
                                    <InputError message={errors.description} />
                                </div>

                                <div className="flex items-center gap-2">
                                    <Checkbox id="is_active" name="is_active" defaultChecked={item.is_active} />
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
                                            defaultValue={existingPrices.no_size || ''}
                                            placeholder="0.00"
                                        />
                                        <InputError message={errors['prices.no_size']} />
                                    </div>

                                    {/* Size-based pricing */}
                                    {sizes.length > 0 && (
                                        <div className="space-y-3">
                                            <div className="text-sm font-medium">Or set prices by size:</div>
                                            {sizes.map((size) => (
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
                                                        defaultValue={existingPrices[String(size.id)] || ''}
                                                        placeholder="0.00"
                                                    />
                                                    <InputError message={errors[`prices.${size.id}`]} />
                                                </div>
                                            ))}
                                        </div>
                                    )}
                                </div>

                                {/* Default Addons Section - for toppings included with the item */}
                                {addonGroups.length > 0 && (
                                    <div className="space-y-4 rounded-lg border border-sidebar-border/50 p-4 dark:border-sidebar-border">
                                        <div>
                                            <h3 className="text-lg font-semibold">Default Toppings/Addons</h3>
                                            <p className="text-sm text-muted-foreground">
                                                Select which toppings/addons are included by default with this item.
                                                These will appear pre-selected when customers order.
                                            </p>
                                        </div>

                                        {addonGroups.map((group) => (
                                            <div key={group.id} className="space-y-3">
                                                <div className="text-sm font-semibold">{group.name}</div>
                                                <div className="grid gap-3 sm:grid-cols-2">
                                                    {group.addons.map((addon) => (
                                                        <div key={addon.id} className="flex items-center gap-2">
                                                            <Checkbox
                                                                id={`addon_${addon.id}`}
                                                                name="default_addons[]"
                                                                value={String(addon.id)}
                                                                defaultChecked={defaultAddonIds.has(addon.id)}
                                                            />
                                                            <Label htmlFor={`addon_${addon.id}`} className="cursor-pointer text-sm">
                                                                {addon.name}
                                                                {addon.type && (
                                                                    <span className="ml-1 text-xs text-muted-foreground">
                                                                        ({addon.type})
                                                                    </span>
                                                                )}
                                                            </Label>
                                                        </div>
                                                    ))}
                                                </div>
                                            </div>
                                        ))}
                                    </div>
                                )}

                                <div className="flex gap-2">
                                    <Button type="submit" disabled={processing}>
                                        {processing ? 'Updating...' : 'Update Menu Item'}
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
