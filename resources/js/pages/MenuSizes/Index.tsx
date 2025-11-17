import { index as menuSizesIndex, create as menuSizesCreate, edit as menuSizesEdit, destroy as menuSizesDestroy } from '@/actions/App/Http/Controllers/MenuSizeController';
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
        title: 'Menu Sizes',
        href: menuSizesIndex().url,
    },
];

interface MenuSize {
    id: number;
    name: string;
    size_note: string | null;
    sort_order: number;
    category: {
        id: number;
        name: string;
    } | null;
}

interface Props {
    sizes: MenuSize[];
}

export default function Index({ sizes }: Props) {
    const handleDelete = (id: number) => {
        if (confirm('Are you sure you want to delete this size?')) {
            router.delete(menuSizesDestroy(id).url);
        }
    };

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Menu Sizes" />
            
            <div className="flex h-full flex-1 flex-col gap-4 overflow-x-auto p-4">
                <div className="flex items-center justify-between">
                    <Heading title="Menu Sizes" />
                    <Link href={menuSizesCreate().url}>
                        <Button>Create Size</Button>
                    </Link>
                </div>

                <div className="rounded-xl border border-sidebar-border/70 dark:border-sidebar-border">
                    <Table>
                        <TableHeader>
                            <TableRow>
                                <TableHead>Name</TableHead>
                                <TableHead>Category</TableHead>
                                <TableHead>Note</TableHead>
                                <TableHead>Sort Order</TableHead>
                                <TableHead className="text-right">Actions</TableHead>
                            </TableRow>
                        </TableHeader>
                        <TableBody>
                            {sizes.length === 0 ? (
                                <TableRow>
                                    <TableCell colSpan={5} className="text-center text-muted-foreground">
                                        No sizes found
                                    </TableCell>
                                </TableRow>
                            ) : (
                                sizes.map((size) => (
                                    <TableRow key={size.id}>
                                        <TableCell className="font-medium">{size.name}</TableCell>
                                        <TableCell>{size.category ? size.category.name : 'All Categories'}</TableCell>
                                        <TableCell>{size.size_note || '-'}</TableCell>
                                        <TableCell>{size.sort_order}</TableCell>
                                        <TableCell className="text-right">
                                            <div className="flex justify-end gap-2">
                                                <Link href={menuSizesEdit(size.id).url}>
                                                    <Button variant="outline" size="sm">
                                                        Edit
                                                    </Button>
                                                </Link>
                                                <Button
                                                    variant="destructive"
                                                    size="sm"
                                                    onClick={() => handleDelete(size.id)}
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
