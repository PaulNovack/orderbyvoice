import AppLayout from '@/layouts/app-layout';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/react';
import { useState } from 'react';
import { Button } from '@/components/ui/button';
import { Checkbox } from '@/components/ui/checkbox';
import { Label } from '@/components/ui/label';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Menu',
        href: '/menu',
    },
    {
        title: 'Item Details',
        href: '#',
    },
];

interface AddonPrice {
    id: number;
    price: number;
    size: {
        id: number;
        name: string;
    };
}

interface Addon {
    id: number;
    name: string;
    type: string;
    is_active: boolean;
    prices: AddonPrice[];
}

interface AddonGroup {
    id: number;
    name: string;
    min_select: number | null;
    max_select: number | null;
    required: boolean;
    addons: Addon[];
}

interface Category {
    id: number;
    name: string;
    addon_groups?: AddonGroup[];
}

interface ItemPrice {
    id: number;
    base_price: number;
    size: {
        id: number;
        name: string;
        size_note: string | null;
    };
}

interface MenuItem {
    id: number;
    name: string;
    description: string | null;
    prices: ItemPrice[];
    category: Category;
    default_addons: Addon[];
}

export default function Show({ item }: { item: MenuItem }) {
    const [selectedSize, setSelectedSize] = useState<number | null>(
        item.prices[0]?.size?.id ?? null
    );
    
    // Initialize selectedAddons with default toppings for each addon group
    const initializeDefaultAddons = (): Record<number, number[]> => {
        const defaults: Record<number, number[]> = {};
        
        item.category.addon_groups?.forEach((group) => {
            const groupDefaults = item.default_addons
                .filter((addon) => group.addons.some((a) => a.id === addon.id))
                .map((addon) => addon.id);
            
            if (groupDefaults.length > 0) {
                defaults[group.id] = groupDefaults;
            }
        });
        
        return defaults;
    };
    
    const [selectedAddons, setSelectedAddons] = useState<Record<number, number[]>>(
        initializeDefaultAddons()
    );

    const selectedPrice = item.prices.find((p) => p.size?.id === selectedSize) || item.prices[0];
    
    const toggleAddon = (groupId: number, addonId: number) => {
        setSelectedAddons((prev) => {
            const groupAddons = prev[groupId] || [];
            const addonIndex = groupAddons.indexOf(addonId);
            
            if (addonIndex > -1) {
                // Remove addon
                return {
                    ...prev,
                    [groupId]: groupAddons.filter((id) => id !== addonId),
                };
            } else {
                // Add addon
                return {
                    ...prev,
                    [groupId]: [...groupAddons, addonId],
                };
            }
        });
    };

    const getAddonPrice = (addon: Addon): number => {
        const addonPrice = addon.prices.find(
            (p) => p.size?.id === selectedSize
        );
        return Number(addonPrice?.price || 0);
    };

    const calculateTotal = (): number => {
        let total = Number(selectedPrice?.base_price || 0);

        // Add addon prices
        item.category.addon_groups?.forEach((group) => {
            const groupSelectedAddons = selectedAddons[group.id] || [];
            groupSelectedAddons.forEach((addonId) => {
                const addon = group.addons.find((a) => a.id === addonId);
                if (addon) {
                    total += getAddonPrice(addon);
                }
            });
        });

        return total;
    };

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title={item.name} />
            <div className="flex h-full flex-1 flex-col gap-6 overflow-x-auto p-6">
                <div className="max-w-3xl mx-auto w-full">
                    <h1 className="text-3xl font-bold">{item.name}</h1>
                    {item.description && (
                        <p className="mt-2 text-lg text-muted-foreground">
                            {item.description}
                        </p>
                    )}

                    {/* Size Selection - only show if item has size variants */}
                    {item.prices.some((p) => p.size) && (
                        <div className="mt-6 space-y-3">
                            <h2 className="text-xl font-semibold">
                                Select Size
                            </h2>
                            <div className="grid gap-2 sm:grid-cols-2 md:grid-cols-4">
                                {item.prices
                                    .filter((p) => p.size)
                                    .map((price) => (
                                        <button
                                            key={price.id}
                                            onClick={() =>
                                                setSelectedSize(price.size!.id)
                                            }
                                            className={`rounded-lg border p-4 text-left transition-all ${
                                                selectedSize === price.size!.id
                                                    ? 'border-primary bg-primary/10 ring-2 ring-primary'
                                                    : 'border-sidebar-border bg-white hover:border-primary dark:bg-sidebar'
                                            }`}
                                        >
                                            <div className="font-semibold">
                                                {price.size!.name}
                                            </div>
                                            {price.size!.size_note && (
                                                <div className="text-sm text-muted-foreground">
                                                    {price.size!.size_note}
                                                </div>
                                            )}
                                            <div className="mt-1 text-lg font-bold text-primary">
                                                $
                                                {Number(
                                                    price.base_price
                                                ).toFixed(2)}
                                            </div>
                                        </button>
                                    ))}
                            </div>
                        </div>
                    )}

                    {/* Add-ons */}
                    {item.category.addon_groups?.map((group) => (
                        <div key={group.id} className="mt-6 space-y-3">
                            <div>
                                <h2 className="text-xl font-semibold">
                                    {group.name}
                                    {group.required && (
                                        <span className="ml-2 text-sm text-red-600">
                                            Required
                                        </span>
                                    )}
                                </h2>
                                {(group.min_select || group.max_select) && (
                                    <p className="text-sm text-muted-foreground">
                                        {group.min_select && group.max_select
                                            ? `Select ${group.min_select} to ${group.max_select} options`
                                            : group.min_select
                                              ? `Select at least ${group.min_select}`
                                              : `Select up to ${group.max_select}`}
                                    </p>
                                )}
                            </div>

                            <div className="space-y-2">
                                {group.addons
                                    .filter((addon) => addon.is_active)
                                    .map((addon) => {
                                        const addonPrice = getAddonPrice(addon);
                                        const isSelected = (
                                            selectedAddons[group.id] || []
                                        ).includes(addon.id);

                                        return (
                                            <div
                                                key={addon.id}
                                                className={`flex items-center justify-between rounded-lg border p-3 transition-all ${
                                                    isSelected
                                                        ? 'border-primary bg-primary/5'
                                                        : 'border-sidebar-border bg-white dark:bg-sidebar'
                                                }`}
                                            >
                                                <div className="flex items-center gap-3">
                                                    <Checkbox
                                                        id={`addon-${addon.id}`}
                                                        checked={isSelected}
                                                        onCheckedChange={() =>
                                                            toggleAddon(
                                                                group.id,
                                                                addon.id
                                                            )
                                                        }
                                                    />
                                                    <Label
                                                        htmlFor={`addon-${addon.id}`}
                                                        className="cursor-pointer"
                                                    >
                                                        {addon.name}
                                                    </Label>
                                                </div>
                                                <div className="font-semibold">
                                                    +${addonPrice.toFixed(2)}
                                                </div>
                                            </div>
                                        );
                                    })}
                            </div>
                        </div>
                    ))}

                    {/* Total and Add to Cart */}
                    <div className="mt-8 sticky bottom-0 bg-white dark:bg-sidebar border-t pt-4">
                        <div className="flex items-center justify-between">
                            <div>
                                <div className="text-sm text-muted-foreground">
                                    Total
                                </div>
                                <div className="text-2xl font-bold">
                                    ${calculateTotal().toFixed(2)}
                                </div>
                            </div>
                            <Button size="lg" className="px-8">
                                Add to Order
                            </Button>
                        </div>
                    </div>
                </div>
            </div>
        </AppLayout>
    );
}
