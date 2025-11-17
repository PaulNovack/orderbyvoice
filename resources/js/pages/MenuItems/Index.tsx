import { index as menuItemsIndex, create as menuItemsCreate, edit as menuItemsEdit, destroy as menuItemsDestroy } from '@/actions/App/Http/Controllers/MenuItemController';
import Heading from '@/components/heading';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import AppLayout from '@/layouts/app-layout';
import { dashboard } from '@/routes';
import { type BreadcrumbItem } from '@/types';
import { Head, Link, router } from '@inertiajs/react';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: dashboard().url,
    },
    {
        title: 'Menu Items',
        href: menuItemsIndex().url,
    },
];

interface MenuItem {
    id: number;
    name: string;
    description: string | null;
    is_active: boolean;
    prices_count: number;
    category: {
        id: number;
        name: string;
    };
}

interface Props {
    items: MenuItem[];
}

export default function Index({ items }: Props) {
    const handleDelete = (id: number) => {
        if (confirm('Are you sure you want to delete this menu item?')) {
            router.delete(menuItemsDestroy(id).url);
        }
    };

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Menu Items" />
            
            <div className="flex h-full flex-1 flex-col gap-4 overflow-x-auto p-4">
                <div className="flex items-center justify-between">
                    <Heading title="Menu Items" />
                    <Link href={menuItemsCreate().url}>
                        <Button>Create Menu Item</Button>
                    </Link>
                </div>

                <div className="rounded-xl border border-sidebar-border/70 dark:border-sidebar-border">
                    <Table>
                        <TableHeader>
                            <TableRow>
                                <TableHead>Name</TableHead>
                                <TableHead>Category</TableHead>
                                <TableHead>Description</TableHead>
                                <TableHead>Prices</TableHead>
                                <TableHead>Status</TableHead>
                                <TableHead className="text-right">Actions</TableHead>
                            </TableRow>
                        </TableHeader>
                        <TableBody>
                            {items.length === 0 ? (
                                <TableRow>
                                    <TableCell colSpan={6} className="text-center text-muted-foreground">
                                        No menu items found
                                    </TableCell>
                                </TableRow>
                            ) : (
                                items.map((item) => (
                                    <TableRow key={item.id}>
                                        <TableCell className="font-medium">{item.name}</TableCell>
                                        <TableCell>{item.category.name}</TableCell>
                                        <TableCell className="max-w-md truncate">
                                            {item.description || '-'}
                                        </TableCell>
                                        <TableCell>{item.prices_count}</TableCell>
                                        <TableCell>
                                            <Badge variant={item.is_active ? 'default' : 'secondary'}>
                                                {item.is_active ? 'Active' : 'Inactive'}
                                            </Badge>
                                        </TableCell>
                                        <TableCell className="text-right">
                                            <div className="flex justify-end gap-2">
                                                <Link href={menuItemsEdit(item.id).url}>
                                                    <Button variant="outline" size="sm">
                                                        Edit
                                                    </Button>
                                                </Link>
                                                <Button
                                                    variant="destructive"
                                                    size="sm"
                                                    onClick={() => handleDelete(item.id)}
                                                >
                                                    Delete
                                                </Button>
                                            </div>
                                        </TableCell>
                                    </TableRow>
                                ))
                            )}
                        </TableBody>
                    </Table>
                </div>
            </div>
        </AppLayout>
    );
}
