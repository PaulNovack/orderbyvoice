import { index as menuCategoriesIndex, create as menuCategoriesCreate, edit as menuCategoriesEdit, destroy as menuCategoriesDestroy } from '@/actions/App/Http/Controllers/MenuCategoryController';
import Heading from '@/components/heading';
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
        title: 'Menu Categories',
        href: menuCategoriesIndex().url,
    },
];

interface MenuCategory {
    id: number;
    name: string;
    slug: string;
    items_count: number;
    created_at: string;
}

interface Props {
    categories: MenuCategory[];
}

export default function Index({ categories }: Props) {
    const handleDelete = (id: number) => {
        if (confirm('Are you sure you want to delete this category?')) {
            router.delete(menuCategoriesDestroy(id).url);
        }
    };

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Menu Categories" />
            
            <div className="flex h-full flex-1 flex-col gap-4 overflow-x-auto p-4">
                <div className="flex items-center justify-between">
                    <Heading title="Menu Categories" />
                    <Link href={menuCategoriesCreate().url}>
                        <Button>Create Category</Button>
                    </Link>
                </div>

                <div className="rounded-xl border border-sidebar-border/70 dark:border-sidebar-border">
                    <Table>
                        <TableHeader>
                            <TableRow>
                                <TableHead>Name</TableHead>
                                <TableHead>Slug</TableHead>
                                <TableHead>Items</TableHead>
                                <TableHead className="text-right">Actions</TableHead>
                            </TableRow>
                        </TableHeader>
                        <TableBody>
                            {categories.length === 0 ? (
                                <TableRow>
                                    <TableCell colSpan={4} className="text-center text-muted-foreground">
                                        No categories found
                                    </TableCell>
                                </TableRow>
                            ) : (
                                categories.map((category) => (
                                    <TableRow key={category.id}>
                                        <TableCell className="font-medium">{category.name}</TableCell>
                                        <TableCell>{category.slug}</TableCell>
                                        <TableCell>{category.items_count}</TableCell>
                                        <TableCell className="text-right">
                                            <div className="flex justify-end gap-2">
                                                <Link href={menuCategoriesEdit(category.id).url}>
                                                    <Button variant="outline" size="sm">
                                                        Edit
                                                    </Button>
                                                </Link>
                                                <Button
                                                    variant="destructive"
                                                    size="sm"
                                                    onClick={() => handleDelete(category.id)}
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
