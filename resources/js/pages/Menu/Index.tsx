import AppLayout from '@/layouts/app-layout';
import { type BreadcrumbItem } from '@/types';
import { Head, Link } from '@inertiajs/react';
import { show as menuShow } from '@/actions/App/Http/Controllers/MenuController';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Menu',
        href: '/menu',
    },
];

interface MenuItem {
    id: number;
    name: string;
    description: string | null;
    is_active: boolean;
    prices: Array<{
        id: number;
        base_price: number;
        size: {
            id: number;
            name: string;
            size_note: string | null;
        } | null;
    }>;
}

interface MenuCategory {
    id: number;
    name: string;
    slug: string;
    items: MenuItem[];
}

export default function Index({
    categories,
}: {
    categories: MenuCategory[];
}) {
    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Menu" />
            <div className="flex h-full flex-1 flex-col gap-6 overflow-x-auto p-6">
                <div className="flex items-center justify-between">
                    <h1 className="text-3xl font-bold">Our Menu</h1>
                </div>

                {categories.map((category) => (
                    <div key={category.id} className="space-y-4">
                        <h2 className="text-2xl font-semibold border-b pb-2">
                            {category.name}
                        </h2>
                        <div className="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
                            {category.items.map((item) => (
                                <Link
                                    key={item.id}
                                    href={menuShow(item.id).url}
                                    className="block rounded-lg border bg-white p-4 shadow-sm transition-all hover:shadow-md dark:bg-sidebar dark:border-sidebar-border"
                                >
                                    <h3 className="text-lg font-semibold">
                                        {item.name}
                                    </h3>
                                    {item.description && (
                                        <p className="mt-2 text-sm text-muted-foreground">
                                            {item.description}
                                        </p>
                                    )}
                                    <div className="mt-3 flex flex-wrap gap-2">
                                        {item.prices
                                            .filter((price) => price.size)
                                            .map((price) => (
                                                <div
                                                    key={price.id}
                                                    className="text-sm"
                                                >
                                                    <span className="font-medium">
                                                        {price.size!.name}
                                                    </span>
                                                    {': '}
                                                    <span className="text-primary">
                                                        $
                                                        {Number(
                                                            price.base_price
                                                        ).toFixed(2)}
                                                    </span>
                                                </div>
                                            ))}
                                    </div>
                                    <div className="mt-3 text-sm text-primary">
                                        Click to customize →
                                    </div>
                                </Link>
                            ))}
                        </div>
                    </div>
                ))}

                {categories.length === 0 && (
                    <div className="text-center text-muted-foreground py-12">
                        No menu items available at this time.
                    </div>
                )}
            </div>
        </AppLayout>
    );
}
