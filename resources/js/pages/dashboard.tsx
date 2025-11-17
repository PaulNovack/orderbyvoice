import { PlaceholderPattern } from '@/components/ui/placeholder-pattern';
import AppLayout from '@/layouts/app-layout';
import { dashboard } from '@/routes';
import { type BreadcrumbItem } from '@/types';
import { Head, Link } from '@inertiajs/react';
import { index as menuCategoriesIndex } from '@/actions/App/Http/Controllers/MenuCategoryController';
import { index as menuItemsIndex } from '@/actions/App/Http/Controllers/MenuItemController';
import { index as menuSizesIndex } from '@/actions/App/Http/Controllers/MenuSizeController';
import { index as menuIndex } from '@/actions/App/Http/Controllers/MenuController';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: dashboard().url,
    },
];

export default function Dashboard() {
    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Dashboard" />
            <div className="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
                <h2 className="text-2xl font-bold">Quick Actions</h2>
                
                <div className="grid auto-rows-min gap-4 md:grid-cols-4">
                    <Link
                        href={menuIndex().url}
                        className="relative aspect-video overflow-hidden rounded-xl border border-primary/50 bg-primary/10 p-6 transition-all hover:border-primary hover:shadow-lg dark:border-primary/50 dark:bg-primary/20"
                    >
                        <div className="flex h-full flex-col justify-between">
                            <div>
                                <h3 className="text-xl font-semibold text-primary">
                                    View Menu
                                </h3>
                                <p className="mt-2 text-sm text-muted-foreground">
                                    Browse & order items
                                </p>
                            </div>
                            <div className="text-sm text-primary">
                                View all →
                            </div>
                        </div>
                    </Link>
                    <Link
                        href={menuCategoriesIndex().url}
                        className="relative aspect-video overflow-hidden rounded-xl border border-sidebar-border/70 bg-white p-6 transition-all hover:border-sidebar-border hover:shadow-lg dark:border-sidebar-border dark:bg-sidebar"
                    >
                        <div className="flex h-full flex-col justify-between">
                            <div>
                                <h3 className="text-xl font-semibold text-sidebar-foreground dark:text-sidebar-foreground">
                                    Menu Categories
                                </h3>
                                <p className="mt-2 text-sm text-muted-foreground">
                                    Manage your menu categories
                                </p>
                            </div>
                            <div className="text-sm text-muted-foreground">
                                View all →
                            </div>
                        </div>
                    </Link>
                    <Link
                        href={menuItemsIndex().url}
                        className="relative aspect-video overflow-hidden rounded-xl border border-sidebar-border/70 bg-white p-6 transition-all hover:border-sidebar-border hover:shadow-lg dark:border-sidebar-border dark:bg-sidebar"
                    >
                        <div className="flex h-full flex-col justify-between">
                            <div>
                                <h3 className="text-xl font-semibold text-sidebar-foreground dark:text-sidebar-foreground">
                                    Menu Items
                                </h3>
                                <p className="mt-2 text-sm text-muted-foreground">
                                    Manage your menu items
                                </p>
                            </div>
                            <div className="text-sm text-muted-foreground">
                                View all →
                            </div>
                        </div>
                    </Link>
                    <Link
                        href={menuSizesIndex().url}
                        className="relative aspect-video overflow-hidden rounded-xl border border-sidebar-border/70 bg-white p-6 transition-all hover:border-sidebar-border hover:shadow-lg dark:border-sidebar-border dark:bg-sidebar"
                    >
                        <div className="flex h-full flex-col justify-between">
                            <div>
                                <h3 className="text-xl font-semibold text-sidebar-foreground dark:text-sidebar-foreground">
                                    Menu Sizes
                                </h3>
                                <p className="mt-2 text-sm text-muted-foreground">
                                    Manage your menu sizes
                                </p>
                            </div>
                            <div className="text-sm text-muted-foreground">
                                View all →
                            </div>
                        </div>
                    </Link>
                </div>
                <div className="relative min-h-[100vh] flex-1 overflow-hidden rounded-xl border border-sidebar-border/70 md:min-h-min dark:border-sidebar-border">
                    <PlaceholderPattern className="absolute inset-0 size-full stroke-neutral-900/20 dark:stroke-neutral-100/20" />
                </div>
            </div>
        </AppLayout>
    );
}
