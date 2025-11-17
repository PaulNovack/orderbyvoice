<?php

namespace Database\Seeders;

use App\Models\Addon;
use App\Models\AddonGroup;
use App\Models\AddonPrice;
use App\Models\Company;
use App\Models\ItemPrice;
use App\Models\MenuCategory;
use App\Models\MenuItem;
use App\Models\MenuSize;
use App\Models\User;
use Illuminate\Database\Seeder;

class RestaurantSeeder extends Seeder
{
    public function run(): void
    {
        $this->seedPizzaRestaurant();
        $this->seedBurgerRestaurant();
        $this->seedItalianRestaurant();
        $this->seedAmysBurgersRestaurant();
    }

    private function seedPizzaRestaurant(): void
    {
        $company = Company::create([
            'name' => "Tony's Pizza Palace",
            'slug' => 'tonys-pizza-palace',
            'description' => 'Authentic New York-style pizza',
            'is_active' => true,
        ]);

        User::create([
            'company_id' => $company->id,
            'name' => 'Tony Soprano',
            'email' => 'tony@pizzapalace.com',
            'password' => bcrypt('password'),
        ]);

        // Categories first
        $pizzaCategory = MenuCategory::create([
            'company_id' => $company->id,
            'name' => 'Pizza',
            'slug' => 'pizza',
        ]);

        $sidesCategory = MenuCategory::create([
            'company_id' => $company->id,
            'name' => 'Sides',
            'slug' => 'sides',
        ]);

        $drinksCategory = MenuCategory::create([
            'company_id' => $company->id,
            'name' => 'Beverages',
            'slug' => 'beverages',
        ]);

        // Pizza Sizes (linked to Pizza category)
        $pizzaSizes = [
            ['name' => '10"', 'size_note' => 'Small', 'sort_order' => 1],
            ['name' => '12"', 'size_note' => 'Medium', 'sort_order' => 2],
            ['name' => '14"', 'size_note' => 'Large', 'sort_order' => 3],
            ['name' => '16"', 'size_note' => 'X-Large', 'sort_order' => 4],
        ];

        foreach ($pizzaSizes as $sizeData) {
            MenuSize::create(array_merge([
                'company_id' => $company->id,
                'category_id' => $pizzaCategory->id,
            ], $sizeData));
        }

        // Drink Sizes (linked to Beverages category)
        $drinkSizes = [
            ['name' => 'Small', 'size_note' => '12 oz', 'sort_order' => 5],
            ['name' => 'Medium', 'size_note' => '16 oz', 'sort_order' => 6],
            ['name' => 'Large', 'size_note' => '20 oz', 'sort_order' => 7],
        ];

        foreach ($drinkSizes as $sizeData) {
            MenuSize::create(array_merge([
                'company_id' => $company->id,
                'category_id' => $drinksCategory->id,
            ], $sizeData));
        }

        // Pizza items
        $pizzas = [
            ['name' => 'Cheese Pizza', 'description' => 'Classic mozzarella and tomato sauce'],
            ['name' => 'Pepperoni Pizza', 'description' => 'Loaded with pepperoni'],
            ['name' => 'Margherita Pizza', 'description' => 'Fresh basil, mozzarella, tomatoes'],
            ['name' => 'Meat Lovers Pizza', 'description' => 'Pepperoni, sausage, bacon, ham'],
            ['name' => 'Veggie Supreme Pizza', 'description' => 'Peppers, onions, mushrooms, olives'],
            ['name' => 'Hawaiian Pizza', 'description' => 'Ham and pineapple'],
        ];

        $pizzaPrices = [8.99, 11.99, 14.99, 17.99];

        foreach ($pizzas as $pizzaData) {
            $pizza = MenuItem::create(array_merge([
                'company_id' => $company->id,
                'category_id' => $pizzaCategory->id,
                'is_active' => true,
            ], $pizzaData));

            // Add prices for each pizza size
            $pizzaSizeModels = MenuSize::where('company_id', $company->id)
                ->whereIn('sort_order', [1, 2, 3, 4])
                ->orderBy('sort_order')
                ->get();

            foreach ($pizzaSizeModels as $index => $size) {
                ItemPrice::create([
                    'item_id' => $pizza->id,
                    'size_id' => $size->id,
                    'base_price' => $pizzaPrices[$index],
                ]);
            }
        }

        // Sides
        $sides = [
            ['name' => 'Garlic Bread', 'description' => 'Toasted with garlic butter', 'price' => 4.99],
            ['name' => 'Mozzarella Sticks', 'description' => '6 pieces with marinara', 'price' => 6.99],
            ['name' => 'Chicken Wings', 'description' => '8 pieces, choice of sauce', 'price' => 9.99],
            ['name' => 'Caesar Salad', 'description' => 'Fresh romaine, parmesan, croutons', 'price' => 5.99],
        ];

        foreach ($sides as $sideData) {
            $price = $sideData['price'];
            unset($sideData['price']);

            $side = MenuItem::create(array_merge([
                'company_id' => $company->id,
                'category_id' => $sidesCategory->id,
                'is_active' => true,
            ], $sideData));

            ItemPrice::create([
                'item_id' => $side->id,
                'size_id' => null,
                'base_price' => $price,
            ]);
        }

        // Beverages with multiple sizes
        $drinks = [
            ['name' => 'Coca-Cola', 'description' => null],
            ['name' => 'Sprite', 'description' => null],
            ['name' => 'Iced Tea', 'description' => null],
            ['name' => 'Water', 'description' => 'Bottled'],
        ];

        $drinkPrices = [1.99, 2.49, 2.99]; // Small, Medium, Large

        $drinkSizeModels = MenuSize::where('company_id', $company->id)
            ->whereIn('sort_order', [5, 6, 7])
            ->orderBy('sort_order')
            ->get();

        foreach ($drinks as $drinkData) {
            $drink = MenuItem::create(array_merge([
                'company_id' => $company->id,
                'category_id' => $drinksCategory->id,
                'is_active' => true,
            ], $drinkData));

            // Add prices for each drink size
            foreach ($drinkSizeModels as $index => $size) {
                ItemPrice::create([
                    'item_id' => $drink->id,
                    'size_id' => $size->id,
                    'base_price' => $drinkPrices[$index],
                ]);
            }
        }

        // Pizza Toppings Addon Group
        $toppingsGroup = AddonGroup::create([
            'company_id' => $company->id,
            'name' => 'Pizza Toppings',
            'applies_to_category_id' => $pizzaCategory->id,
            'applies_to_item_id' => null,
            'min_select' => 0,
            'max_select' => null,
            'required' => false,
        ]);

        $toppings = [
            ['name' => 'Pepperoni', 'type' => 'meat'],
            ['name' => 'Sausage', 'type' => 'meat'],
            ['name' => 'Bacon', 'type' => 'meat'],
            ['name' => 'Ham', 'type' => 'meat'],
            ['name' => 'Chicken', 'type' => 'meat'],
            ['name' => 'Mushrooms', 'type' => 'veg'],
            ['name' => 'Green Peppers', 'type' => 'veg'],
            ['name' => 'Onions', 'type' => 'veg'],
            ['name' => 'Black Olives', 'type' => 'veg'],
            ['name' => 'Tomatoes', 'type' => 'veg'],
            ['name' => 'Pineapple', 'type' => 'veg'],
            ['name' => 'Extra Cheese', 'type' => 'cheese'],
            ['name' => 'Fresh Basil', 'type' => 'veg'],
            ['name' => 'Mozzarella', 'type' => 'cheese'],
        ];

        $toppingPrices = [1.50, 2.00, 2.50, 3.00];

        $toppingsByName = [];

        foreach ($toppings as $toppingData) {
            $topping = Addon::create(array_merge([
                'addon_group_id' => $toppingsGroup->id,
                'is_active' => true,
            ], $toppingData));

            $toppingsByName[$toppingData['name']] = $topping;

            // Add prices for each pizza size
            $pizzaSizeModels = MenuSize::where('company_id', $company->id)
                ->whereIn('sort_order', [1, 2, 3, 4])
                ->orderBy('sort_order')
                ->get();

            foreach ($pizzaSizeModels as $index => $size) {
                AddonPrice::create([
                    'addon_id' => $topping->id,
                    'size_id' => $size->id,
                    'price' => $toppingPrices[$index],
                ]);
            }
        }

        // Set default toppings for each pizza
        $pizzaItems = MenuItem::where('company_id', $company->id)
            ->where('category_id', $pizzaCategory->id)
            ->get()
            ->keyBy('name');

        // Cheese Pizza: Mozzarella
        if (isset($pizzaItems['Cheese Pizza']) && isset($toppingsByName['Mozzarella'])) {
            $pizzaItems['Cheese Pizza']->defaultAddons()->attach($toppingsByName['Mozzarella']->id);
        }

        // Pepperoni Pizza: Mozzarella, Pepperoni
        if (isset($pizzaItems['Pepperoni Pizza'])) {
            $pizzaItems['Pepperoni Pizza']->defaultAddons()->attach([
                $toppingsByName['Mozzarella']->id,
                $toppingsByName['Pepperoni']->id,
            ]);
        }

        // Margherita Pizza: Fresh Basil, Mozzarella, Tomatoes
        if (isset($pizzaItems['Margherita Pizza'])) {
            $pizzaItems['Margherita Pizza']->defaultAddons()->attach([
                $toppingsByName['Fresh Basil']->id,
                $toppingsByName['Mozzarella']->id,
                $toppingsByName['Tomatoes']->id,
            ]);
        }

        // Meat Lovers Pizza: Pepperoni, Sausage, Bacon, Ham, Mozzarella
        if (isset($pizzaItems['Meat Lovers Pizza'])) {
            $pizzaItems['Meat Lovers Pizza']->defaultAddons()->attach([
                $toppingsByName['Pepperoni']->id,
                $toppingsByName['Sausage']->id,
                $toppingsByName['Bacon']->id,
                $toppingsByName['Ham']->id,
                $toppingsByName['Mozzarella']->id,
            ]);
        }

        // Veggie Supreme Pizza: Green Peppers, Onions, Mushrooms, Black Olives, Mozzarella
        if (isset($pizzaItems['Veggie Supreme Pizza'])) {
            $pizzaItems['Veggie Supreme Pizza']->defaultAddons()->attach([
                $toppingsByName['Green Peppers']->id,
                $toppingsByName['Onions']->id,
                $toppingsByName['Mushrooms']->id,
                $toppingsByName['Black Olives']->id,
                $toppingsByName['Mozzarella']->id,
            ]);
        }

        // Hawaiian Pizza: Ham, Pineapple, Mozzarella
        if (isset($pizzaItems['Hawaiian Pizza'])) {
            $pizzaItems['Hawaiian Pizza']->defaultAddons()->attach([
                $toppingsByName['Ham']->id,
                $toppingsByName['Pineapple']->id,
                $toppingsByName['Mozzarella']->id,
            ]);
        }
    }

    private function seedBurgerRestaurant(): void
    {
        $company = Company::create([
            'name' => "Bob's Burger Joint",
            'slug' => 'bobs-burger-joint',
            'description' => 'The best burgers in town',
            'is_active' => true,
        ]);

        User::create([
            'company_id' => $company->id,
            'name' => 'Bob Belcher',
            'email' => 'bob@burgers.com',
            'password' => bcrypt('password'),
        ]);

        // Categories first
        $burgersCategory = MenuCategory::create([
            'company_id' => $company->id,
            'name' => 'Burgers',
            'slug' => 'burgers',
        ]);

        $sidesCategory = MenuCategory::create([
            'company_id' => $company->id,
            'name' => 'Sides',
            'slug' => 'sides',
        ]);

        $drinksCategory = MenuCategory::create([
            'company_id' => $company->id,
            'name' => 'Drinks',
            'slug' => 'drinks',
        ]);

        // Burger Sizes (linked to Burgers category)
        $burgerSizes = [
            ['name' => 'Single', 'size_note' => '1/3 lb', 'sort_order' => 1],
            ['name' => 'Double', 'size_note' => '2/3 lb', 'sort_order' => 2],
            ['name' => 'Triple', 'size_note' => '1 lb', 'sort_order' => 3],
        ];

        foreach ($burgerSizes as $sizeData) {
            MenuSize::create(array_merge([
                'company_id' => $company->id,
                'category_id' => $burgersCategory->id,
            ], $sizeData));
        }

        // Drink Sizes (linked to Drinks category)
        $drinkSizes = [
            ['name' => 'Regular', 'size_note' => '16 oz', 'sort_order' => 4],
            ['name' => 'Large', 'size_note' => '24 oz', 'sort_order' => 5],
            ['name' => 'Extra-Large', 'size_note' => '32 oz', 'sort_order' => 6],
        ];

        foreach ($drinkSizes as $sizeData) {
            MenuSize::create(array_merge([
                'company_id' => $company->id,
                'category_id' => $drinksCategory->id,
            ], $sizeData));
        }

        // Burgers
        $burgers = [
            ['name' => 'Classic Burger', 'description' => 'Lettuce, tomato, onion, pickles'],
            ['name' => 'Cheeseburger', 'description' => 'American cheese, lettuce, tomato'],
            ['name' => 'Bacon Burger', 'description' => 'Crispy bacon, cheddar, BBQ sauce'],
            ['name' => 'Mushroom Swiss Burger', 'description' => 'Sautéed mushrooms, Swiss cheese'],
            ['name' => 'Spicy Jalapeño Burger', 'description' => 'Jalapeños, pepper jack, chipotle mayo'],
        ];

        $burgerPrices = [7.99, 9.99, 11.99];

        foreach ($burgers as $burgerData) {
            $burger = MenuItem::create(array_merge([
                'company_id' => $company->id,
                'category_id' => $burgersCategory->id,
                'is_active' => true,
            ], $burgerData));

            // Add prices for each burger size
            $burgerSizeModels = MenuSize::where('company_id', $company->id)
                ->whereIn('sort_order', [1, 2, 3])
                ->orderBy('sort_order')
                ->get();

            foreach ($burgerSizeModels as $index => $size) {
                ItemPrice::create([
                    'item_id' => $burger->id,
                    'size_id' => $size->id,
                    'base_price' => $burgerPrices[$index],
                ]);
            }
        }

        // Sides
        $sides = [
            ['name' => 'French Fries', 'description' => 'Crispy golden fries', 'price' => 3.99],
            ['name' => 'Onion Rings', 'description' => 'Beer-battered onion rings', 'price' => 4.99],
            ['name' => 'Sweet Potato Fries', 'description' => 'With honey mustard', 'price' => 4.99],
            ['name' => 'Coleslaw', 'description' => 'Creamy homemade coleslaw', 'price' => 2.99],
        ];

        foreach ($sides as $sideData) {
            $price = $sideData['price'];
            unset($sideData['price']);

            $side = MenuItem::create(array_merge([
                'company_id' => $company->id,
                'category_id' => $sidesCategory->id,
                'is_active' => true,
            ], $sideData));

            ItemPrice::create([
                'item_id' => $side->id,
                'size_id' => null,
                'base_price' => $price,
            ]);
        }

        // Drinks with multiple sizes
        $drinks = [
            ['name' => 'Fountain Soda', 'description' => 'Your choice'],
            ['name' => 'Milkshake', 'description' => 'Chocolate, vanilla, or strawberry'],
            ['name' => 'Lemonade', 'description' => 'Fresh squeezed'],
        ];

        $drinkPrices = [2.49, 2.99, 3.49]; // Regular, Large, Extra-Large

        $drinkSizeModels = MenuSize::where('company_id', $company->id)
            ->whereIn('sort_order', [4, 5, 6])
            ->orderBy('sort_order')
            ->get();

        foreach ($drinks as $drinkData) {
            $drink = MenuItem::create(array_merge([
                'company_id' => $company->id,
                'category_id' => $drinksCategory->id,
                'is_active' => true,
            ], $drinkData));

            // Add prices for each drink size
            foreach ($drinkSizeModels as $index => $size) {
                ItemPrice::create([
                    'item_id' => $drink->id,
                    'size_id' => $size->id,
                    'base_price' => $drinkPrices[$index],
                ]);
            }
        }

        // Burger Toppings
        $toppingsGroup = AddonGroup::create([
            'company_id' => $company->id,
            'name' => 'Extra Toppings',
            'applies_to_category_id' => $burgersCategory->id,
            'applies_to_item_id' => null,
            'min_select' => 0,
            'max_select' => null,
            'required' => false,
        ]);

        $toppings = [
            ['name' => 'Extra Cheese', 'type' => 'cheese', 'price' => 1.00],
            ['name' => 'Bacon', 'type' => 'meat', 'price' => 2.00],
            ['name' => 'Fried Egg', 'type' => 'protein', 'price' => 1.50],
            ['name' => 'Avocado', 'type' => 'veg', 'price' => 2.00],
            ['name' => 'Sautéed Mushrooms', 'type' => 'veg', 'price' => 1.50],
            ['name' => 'Jalapeños', 'type' => 'veg', 'price' => 0.50],
            ['name' => 'Grilled Onions', 'type' => 'veg', 'price' => 0.50],
        ];

        foreach ($toppings as $toppingData) {
            $price = $toppingData['price'];
            unset($toppingData['price']);

            $topping = Addon::create(array_merge([
                'addon_group_id' => $toppingsGroup->id,
                'is_active' => true,
            ], $toppingData));

            AddonPrice::create([
                'addon_id' => $topping->id,
                'size_id' => null,
                'price' => $price,
            ]);
        }
    }

    private function seedItalianRestaurant(): void
    {
        $company = Company::create([
            'name' => "Mama Mia's Italian Kitchen",
            'slug' => 'mama-mias-italian',
            'description' => 'Authentic Italian cuisine from the old country',
            'is_active' => true,
        ]);

        User::create([
            'company_id' => $company->id,
            'name' => 'Giovanni Romano',
            'email' => 'giovanni@mamamias.com',
            'password' => bcrypt('password'),
        ]);

        // Categories
        $pastaCategory = MenuCategory::create([
            'company_id' => $company->id,
            'name' => 'Pasta',
            'slug' => 'pasta',
        ]);

        $entreesCategory = MenuCategory::create([
            'company_id' => $company->id,
            'name' => 'Entrées',
            'slug' => 'entrees',
        ]);

        $appetizersCategory = MenuCategory::create([
            'company_id' => $company->id,
            'name' => 'Appetizers',
            'slug' => 'appetizers',
        ]);

        $dessertsCategory = MenuCategory::create([
            'company_id' => $company->id,
            'name' => 'Desserts',
            'slug' => 'desserts',
        ]);

        // Pasta Dishes
        $pastas = [
            ['name' => 'Spaghetti Carbonara', 'description' => 'Pancetta, egg, parmesan, black pepper', 'price' => 16.99],
            ['name' => 'Fettuccine Alfredo', 'description' => 'Creamy parmesan sauce', 'price' => 15.99],
            ['name' => 'Penne Arrabbiata', 'description' => 'Spicy tomato sauce', 'price' => 14.99],
            ['name' => 'Lasagna Bolognese', 'description' => 'Layers of pasta, meat sauce, béchamel', 'price' => 17.99],
            ['name' => 'Ravioli di Ricotta', 'description' => 'Ricotta-filled ravioli in sage butter', 'price' => 16.99],
        ];

        foreach ($pastas as $pastaData) {
            $price = $pastaData['price'];
            unset($pastaData['price']);

            $pasta = MenuItem::create(array_merge([
                'company_id' => $company->id,
                'category_id' => $pastaCategory->id,
                'is_active' => true,
            ], $pastaData));

            ItemPrice::create([
                'item_id' => $pasta->id,
                'size_id' => null,
                'base_price' => $price,
            ]);
        }

        // Entrées
        $entrees = [
            ['name' => 'Chicken Parmesan', 'description' => 'Breaded chicken, marinara, mozzarella', 'price' => 18.99],
            ['name' => 'Veal Marsala', 'description' => 'Veal scaloppine in Marsala wine sauce', 'price' => 22.99],
            ['name' => 'Eggplant Parmesan', 'description' => 'Layered eggplant, marinara, cheese', 'price' => 16.99],
            ['name' => 'Osso Buco', 'description' => 'Braised veal shanks in white wine', 'price' => 26.99],
        ];

        foreach ($entrees as $entreeData) {
            $price = $entreeData['price'];
            unset($entreeData['price']);

            $entree = MenuItem::create(array_merge([
                'company_id' => $company->id,
                'category_id' => $entreesCategory->id,
                'is_active' => true,
            ], $entreeData));

            ItemPrice::create([
                'item_id' => $entree->id,
                'size_id' => null,
                'base_price' => $price,
            ]);
        }

        // Appetizers
        $appetizers = [
            ['name' => 'Bruschetta', 'description' => 'Toasted bread, tomatoes, basil, garlic', 'price' => 8.99],
            ['name' => 'Calamari Fritti', 'description' => 'Fried squid with marinara', 'price' => 11.99],
            ['name' => 'Caprese Salad', 'description' => 'Fresh mozzarella, tomatoes, basil', 'price' => 9.99],
            ['name' => 'Antipasto Platter', 'description' => 'Cured meats, cheeses, olives', 'price' => 14.99],
        ];

        foreach ($appetizers as $appetizerData) {
            $price = $appetizerData['price'];
            unset($appetizerData['price']);

            $appetizer = MenuItem::create(array_merge([
                'company_id' => $company->id,
                'category_id' => $appetizersCategory->id,
                'is_active' => true,
            ], $appetizerData));

            ItemPrice::create([
                'item_id' => $appetizer->id,
                'size_id' => null,
                'base_price' => $price,
            ]);
        }

        // Desserts
        $desserts = [
            ['name' => 'Tiramisu', 'description' => 'Espresso-soaked ladyfingers, mascarpone', 'price' => 7.99],
            ['name' => 'Panna Cotta', 'description' => 'Vanilla cream with berry compote', 'price' => 6.99],
            ['name' => 'Cannoli', 'description' => 'Crispy shells filled with sweet ricotta', 'price' => 6.99],
            ['name' => 'Gelato', 'description' => 'Italian ice cream, various flavors', 'price' => 5.99],
        ];

        foreach ($desserts as $dessertData) {
            $price = $dessertData['price'];
            unset($dessertData['price']);

            $dessert = MenuItem::create(array_merge([
                'company_id' => $company->id,
                'category_id' => $dessertsCategory->id,
                'is_active' => true,
            ], $dessertData));

            ItemPrice::create([
                'item_id' => $dessert->id,
                'size_id' => null,
                'base_price' => $price,
            ]);
        }

        // Protein Add-ons for Pasta
        $proteinGroup = AddonGroup::create([
            'company_id' => $company->id,
            'name' => 'Add Protein',
            'applies_to_category_id' => $pastaCategory->id,
            'applies_to_item_id' => null,
            'min_select' => 0,
            'max_select' => 2,
            'required' => false,
        ]);

        $proteins = [
            ['name' => 'Grilled Chicken', 'type' => 'protein', 'price' => 5.00],
            ['name' => 'Italian Sausage', 'type' => 'protein', 'price' => 5.00],
            ['name' => 'Meatballs', 'type' => 'protein', 'price' => 4.00],
            ['name' => 'Shrimp', 'type' => 'protein', 'price' => 7.00],
        ];

        foreach ($proteins as $proteinData) {
            $price = $proteinData['price'];
            unset($proteinData['price']);

            $protein = Addon::create(array_merge([
                'addon_group_id' => $proteinGroup->id,
                'is_active' => true,
            ], $proteinData));

            AddonPrice::create([
                'addon_id' => $protein->id,
                'size_id' => null,
                'price' => $price,
            ]);
        }
    }

    private function seedAmysBurgersRestaurant(): void
    {
        $company = Company::create([
            'name' => "Amy's Burgers",
            'slug' => 'amys-burgers',
            'description' => 'Delicious burgers and more',
            'is_active' => true,
        ]);

        User::create([
            'company_id' => $company->id,
            'name' => 'Amy Johnson',
            'email' => 'amy@amysburgers.com',
            'password' => bcrypt('password'),
        ]);

        // Categories
        $burgersCategory = MenuCategory::create([
            'company_id' => $company->id,
            'name' => 'Burgers',
            'slug' => 'burgers',
        ]);

        $sidesCategory = MenuCategory::create([
            'company_id' => $company->id,
            'name' => 'Sides',
            'slug' => 'sides',
        ]);

        $drinksCategory = MenuCategory::create([
            'company_id' => $company->id,
            'name' => 'Drinks',
            'slug' => 'drinks',
        ]);

        // Side Sizes (Regular/Large) - linked to Sides category
        $regularSize = MenuSize::create([
            'company_id' => $company->id,
            'category_id' => $sidesCategory->id,
            'name' => 'Regular',
            'size_note' => null,
            'sort_order' => 1,
        ]);

        $largeSideSize = MenuSize::create([
            'company_id' => $company->id,
            'category_id' => $sidesCategory->id,
            'name' => 'Large',
            'size_note' => null,
            'sort_order' => 2,
        ]);

        // Drink Sizes (Regular/Large) - linked to Drinks category
        $regularDrinkSize = MenuSize::create([
            'company_id' => $company->id,
            'category_id' => $drinksCategory->id,
            'name' => 'Regular',
            'size_note' => null,
            'sort_order' => 3,
        ]);

        $largeDrinkSize = MenuSize::create([
            'company_id' => $company->id,
            'category_id' => $drinksCategory->id,
            'name' => 'Large',
            'size_note' => null,
            'sort_order' => 4,
        ]);

        // Burgers (base price adjusted so base + addons = exact menu price)
        $burgers = [
            ['name' => '#1 Classic Hamburger', 'description' => 'Beef Patty, Lettuce, Tomato, Onion, Pickles', 'price' => 2.24], // 5.99 - 3.75
            ['name' => '#2 Cheeseburger', 'description' => 'Beef Patty, Cheddar Cheese, Lettuce, Tomato, Onion, Pickles', 'price' => 1.74], // 6.49 - 4.75
            ['name' => '#3 Bacon Burger', 'description' => 'Beef Patty, Bacon, Cheddar Cheese, BBQ Sauce', 'price' => 1.49], // 7.49 - 6.00
            ['name' => '#4 Mushroom Swiss Burger', 'description' => 'Beef Patty, Swiss Cheese, Grilled Mushrooms', 'price' => 1.04], // 7.29 - 6.25
            ['name' => '#5 BBQ Burger', 'description' => 'Beef Patty, Onion Rings, BBQ Sauce, Cheddar Cheese', 'price' => 1.09], // 7.59 - 6.50
            ['name' => '#6 Double Cheeseburger', 'description' => '2 Beef Patties, American Cheese, Lettuce, Tomato', 'price' => 0.99], // 8.49 - 7.50
            ['name' => '#7 Veggie Burger', 'description' => 'Veggie Patty, Lettuce, Tomato, Onion, Avocado', 'price' => 1.49], // 6.99 - 5.50
            ['name' => '#8 Spicy Jalapeño Burger', 'description' => 'Beef Patty, Pepper Jack Cheese, Jalapeños, Chipotle Mayo', 'price' => 1.69], // 7.19 - 5.50
            ['name' => '#9 Blue Cheese Burger', 'description' => 'Beef Patty, Blue Cheese Crumbles, Caramelized Onions', 'price' => 1.14], // 7.39 - 6.25
            ['name' => '#10 Quarter Pound Burger', 'description' => 'Quarter Pound Beef Patty, Lettuce, Tomato, Onion', 'price' => 1.79], // 6.79 - 5.00
            ['name' => '#11 BBQ Bacon Burger', 'description' => 'Beef Patty, Bacon, BBQ Sauce, Cheddar Cheese', 'price' => 1.29], // 7.79 - 6.50
            ['name' => '#12 Classic Double', 'description' => '2 Beef Patties, Lettuce, Tomato, Pickles, Onion', 'price' => 1.69], // 8.19 - 6.50
        ];

        foreach ($burgers as $burgerData) {
            $price = $burgerData['price'];
            unset($burgerData['price']);

            $burger = MenuItem::create(array_merge([
                'company_id' => $company->id,
                'category_id' => $burgersCategory->id,
                'is_active' => true,
            ], $burgerData));

            ItemPrice::create([
                'item_id' => $burger->id,
                'size_id' => null,
                'base_price' => $price,
            ]);
        }

        // Sides with Regular and Large sizes
        $sides = [
            ['name' => 'Chili Cheese Fries', 'description' => null, 'regular' => 5.49, 'large' => 6.49],
            ['name' => 'Coleslaw', 'description' => null, 'regular' => 2.49, 'large' => 3.49],
            ['name' => 'Curly Fries', 'description' => null, 'regular' => 3.49, 'large' => 4.49],
            ['name' => 'French Fries', 'description' => null, 'regular' => 2.99, 'large' => 3.99],
            ['name' => 'Garlic Parmesan Fries', 'description' => null, 'regular' => 4.49, 'large' => 5.49],
            ['name' => 'Mac & Cheese Bites', 'description' => null, 'regular' => 4.29, 'large' => 5.29],
            ['name' => 'Mozzarella Sticks', 'description' => null, 'regular' => 4.99, 'large' => 5.99],
            ['name' => 'Onion Rings', 'description' => null, 'regular' => 3.99, 'large' => 4.99],
            ['name' => 'Pickle Chips', 'description' => null, 'regular' => 2.79, 'large' => 3.79],
            ['name' => 'Side Salad', 'description' => null, 'regular' => 3.49, 'large' => 4.49],
            ['name' => 'Sweet Potato Fries', 'description' => null, 'regular' => 3.99, 'large' => 4.99],
            ['name' => 'Tater Tots', 'description' => null, 'regular' => 3.29, 'large' => 4.29],
        ];

        foreach ($sides as $sideData) {
            $regularPrice = $sideData['regular'];
            $largePrice = $sideData['large'];
            unset($sideData['regular'], $sideData['large']);

            $side = MenuItem::create(array_merge([
                'company_id' => $company->id,
                'category_id' => $sidesCategory->id,
                'is_active' => true,
            ], $sideData));

            // Add prices for each side size
            ItemPrice::create([
                'item_id' => $side->id,
                'size_id' => $regularSize->id,
                'base_price' => $regularPrice,
            ]);

            ItemPrice::create([
                'item_id' => $side->id,
                'size_id' => $largeSideSize->id,
                'base_price' => $largePrice,
            ]);
        }

        // Drinks with Regular and Large sizes
        $drinks = [
            ['name' => 'Chocolate Milkshake', 'description' => null, 'regular' => 3.49, 'large' => 4.49],
            ['name' => 'Coke', 'description' => null, 'regular' => 1.99, 'large' => 2.49],
            ['name' => 'Diet Coke', 'description' => null, 'regular' => 1.99, 'large' => 2.49],
            ['name' => 'Iced Tea', 'description' => null, 'regular' => 1.79, 'large' => 2.29],
            ['name' => 'Lemonade', 'description' => null, 'regular' => 1.99, 'large' => 2.49],
            ['name' => 'Root Beer', 'description' => null, 'regular' => 1.99, 'large' => 2.49],
            ['name' => 'Sprite', 'description' => null, 'regular' => 1.99, 'large' => 2.49],
            ['name' => 'Vanilla Milkshake', 'description' => null, 'regular' => 3.49, 'large' => 4.49],
        ];

        foreach ($drinks as $drinkData) {
            $regularPrice = $drinkData['regular'];
            $largePrice = $drinkData['large'];
            unset($drinkData['regular'], $drinkData['large']);

            $drink = MenuItem::create(array_merge([
                'company_id' => $company->id,
                'category_id' => $drinksCategory->id,
                'is_active' => true,
            ], $drinkData));

            // Add prices for each drink size
            ItemPrice::create([
                'item_id' => $drink->id,
                'size_id' => $regularDrinkSize->id,
                'base_price' => $regularPrice,
            ]);

            ItemPrice::create([
                'item_id' => $drink->id,
                'size_id' => $largeDrinkSize->id,
                'base_price' => $largePrice,
            ]);
        }

        // Burger Toppings
        $toppingsGroup = AddonGroup::create([
            'company_id' => $company->id,
            'name' => 'Burger Toppings',
            'applies_to_category_id' => $burgersCategory->id,
            'applies_to_item_id' => null,
            'min_select' => 0,
            'max_select' => null,
            'required' => false,
        ]);

        $toppings = [
            // Meats/Proteins - adjusted so total adds up correctly
            ['name' => 'Beef Patty', 'type' => 'meat', 'price' => 2.75], // Single patty
            ['name' => 'Double Patty', 'type' => 'meat', 'price' => 5.50], // For double burgers (2x patties)
            ['name' => 'Quarter Pound Patty', 'type' => 'meat', 'price' => 3.00], // Quarter pounder
            ['name' => 'Veggie Patty', 'type' => 'meat', 'price' => 2.75], // Veggie option
            ['name' => 'Bacon', 'type' => 'meat', 'price' => 1.50],

            // Cheeses
            ['name' => 'Cheddar Cheese', 'type' => 'cheese', 'price' => 1.00],
            ['name' => 'American Cheese', 'type' => 'cheese', 'price' => 1.00],
            ['name' => 'Swiss Cheese', 'type' => 'cheese', 'price' => 1.00],
            ['name' => 'Pepper Jack Cheese', 'type' => 'cheese', 'price' => 1.00],
            ['name' => 'Blue Cheese Crumbles', 'type' => 'cheese', 'price' => 1.50],

            // Vegetables
            ['name' => 'Lettuce', 'type' => 'veg', 'price' => 0.25],
            ['name' => 'Tomato', 'type' => 'veg', 'price' => 0.25],
            ['name' => 'Onion', 'type' => 'veg', 'price' => 0.25],
            ['name' => 'Pickles', 'type' => 'veg', 'price' => 0.25],
            ['name' => 'Jalapeños', 'type' => 'veg', 'price' => 0.50],
            ['name' => 'Grilled Mushrooms', 'type' => 'veg', 'price' => 1.50],
            ['name' => 'Caramelized Onions', 'type' => 'veg', 'price' => 1.25],
            ['name' => 'Avocado', 'type' => 'veg', 'price' => 1.75],
            ['name' => 'Onion Rings', 'type' => 'veg', 'price' => 1.75],

            // Sauces
            ['name' => 'BBQ Sauce', 'type' => 'sauce', 'price' => 0.25],
            ['name' => 'Chipotle Mayo', 'type' => 'sauce', 'price' => 0.25],
            ['name' => 'Ketchup', 'type' => 'sauce', 'price' => 0.00],
            ['name' => 'Mustard', 'type' => 'sauce', 'price' => 0.00],
        ];

        $toppingsByName = [];

        foreach ($toppings as $toppingData) {
            $price = $toppingData['price'];
            unset($toppingData['price']);

            $topping = Addon::create(array_merge([
                'addon_group_id' => $toppingsGroup->id,
                'is_active' => true,
            ], $toppingData));

            // Add price for this topping
            AddonPrice::create([
                'addon_id' => $topping->id,
                'size_id' => null,
                'price' => $price,
            ]);

            $toppingsByName[$toppingData['name']] = $topping;
        }

        // Set default toppings for each burger
        $burgerItems = MenuItem::where('company_id', $company->id)
            ->where('category_id', $burgersCategory->id)
            ->get()
            ->keyBy('name');

        // #1 Classic Hamburger ($5.99): Base $0.50 + Patty $2.75 + 4 veggies $1.00 + Extras $1.74
        if (isset($burgerItems['#1 Classic Hamburger'])) {
            $burgerItems['#1 Classic Hamburger']->defaultAddons()->attach([
                $toppingsByName['Beef Patty']->id,
                $toppingsByName['Lettuce']->id,
                $toppingsByName['Tomato']->id,
                $toppingsByName['Onion']->id,
                $toppingsByName['Pickles']->id,
            ]);
        }

        // #2 Cheeseburger ($6.49): Base $0.50 + Patty $2.75 + Cheese $1.00 + 4 veggies $1.00 + Extras $1.24
        if (isset($burgerItems['#2 Cheeseburger'])) {
            $burgerItems['#2 Cheeseburger']->defaultAddons()->attach([
                $toppingsByName['Beef Patty']->id,
                $toppingsByName['Cheddar Cheese']->id,
                $toppingsByName['Lettuce']->id,
                $toppingsByName['Tomato']->id,
                $toppingsByName['Onion']->id,
                $toppingsByName['Pickles']->id,
            ]);
        }

        // #3 Bacon Burger ($7.49): Base $0.50 + Patty $2.75 + Bacon $1.50 + Cheese $1.00 + BBQ $0.25 = $6.00 (need $1.49 more)
        if (isset($burgerItems['#3 Bacon Burger'])) {
            $burgerItems['#3 Bacon Burger']->defaultAddons()->attach([
                $toppingsByName['Beef Patty']->id,
                $toppingsByName['Bacon']->id,
                $toppingsByName['Cheddar Cheese']->id,
                $toppingsByName['BBQ Sauce']->id,
                $toppingsByName['Lettuce']->id, // Added to make up price
                $toppingsByName['Tomato']->id, // Added to make up price
            ]);
        }

        // #4 Mushroom Swiss Burger ($7.29): Base $0.50 + Patty $2.75 + Swiss $1.00 + Mushrooms $1.50 = $5.75 (need $1.54 more)
        if (isset($burgerItems['#4 Mushroom Swiss Burger'])) {
            $burgerItems['#4 Mushroom Swiss Burger']->defaultAddons()->attach([
                $toppingsByName['Beef Patty']->id,
                $toppingsByName['Swiss Cheese']->id,
                $toppingsByName['Grilled Mushrooms']->id,
                $toppingsByName['Lettuce']->id, // Added
                $toppingsByName['Tomato']->id, // Added
                $toppingsByName['Onion']->id, // Added
                $toppingsByName['Pickles']->id, // Added
            ]);
        }

        // #5 BBQ Burger ($7.59): Base $0.50 + Patty $2.75 + Onion Rings $1.75 + BBQ $0.25 + Cheese $1.00 = $6.25 (need $1.34 more)
        if (isset($burgerItems['#5 BBQ Burger'])) {
            $burgerItems['#5 BBQ Burger']->defaultAddons()->attach([
                $toppingsByName['Beef Patty']->id,
                $toppingsByName['Onion Rings']->id,
                $toppingsByName['BBQ Sauce']->id,
                $toppingsByName['Cheddar Cheese']->id,
                $toppingsByName['Lettuce']->id, // Added
                $toppingsByName['Tomato']->id, // Added
                $toppingsByName['Pickles']->id, // Added
            ]);
        }

        // #6 Double Cheeseburger ($8.49): Base $0.50 + Double Patty $5.50 + American $1.00 + Lettuce $0.25 + Tomato $0.25 = $7.50 (need $0.99 more)
        if (isset($burgerItems['#6 Double Cheeseburger'])) {
            $burgerItems['#6 Double Cheeseburger']->defaultAddons()->attach([
                $toppingsByName['Double Patty']->id,
                $toppingsByName['American Cheese']->id,
                $toppingsByName['Lettuce']->id,
                $toppingsByName['Tomato']->id,
                $toppingsByName['Onion']->id, // Added
                $toppingsByName['Pickles']->id, // Added
            ]);
        }

        // #7 Veggie Burger ($6.99): Base $0.50 + Veggie Patty $2.75 + Lettuce $0.25 + Tomato $0.25 + Onion $0.25 + Avocado $1.75 = $5.75 (need $1.24 more)
        if (isset($burgerItems['#7 Veggie Burger'])) {
            $burgerItems['#7 Veggie Burger']->defaultAddons()->attach([
                $toppingsByName['Veggie Patty']->id,
                $toppingsByName['Lettuce']->id,
                $toppingsByName['Tomato']->id,
                $toppingsByName['Onion']->id,
                $toppingsByName['Avocado']->id,
                $toppingsByName['Pickles']->id, // Added
            ]);
        }

        // #8 Spicy Jalapeño Burger ($7.19): Base $0.50 + Patty $2.75 + Pepper Jack $1.00 + Jalapeños $0.50 + Chipotle $0.25 = $5.00 (need $2.19 more)
        if (isset($burgerItems['#8 Spicy Jalapeño Burger'])) {
            $burgerItems['#8 Spicy Jalapeño Burger']->defaultAddons()->attach([
                $toppingsByName['Beef Patty']->id,
                $toppingsByName['Pepper Jack Cheese']->id,
                $toppingsByName['Jalapeños']->id,
                $toppingsByName['Chipotle Mayo']->id,
                $toppingsByName['Lettuce']->id, // Added
                $toppingsByName['Tomato']->id, // Added
                $toppingsByName['Onion']->id, // Added
                $toppingsByName['Pickles']->id, // Added
            ]);
        }

        // #9 Blue Cheese Burger ($7.39): Base $0.50 + Patty $2.75 + Blue Cheese $1.50 + Caramelized Onions $1.25 = $6.00 (need $1.39 more)
        if (isset($burgerItems['#9 Blue Cheese Burger'])) {
            $burgerItems['#9 Blue Cheese Burger']->defaultAddons()->attach([
                $toppingsByName['Beef Patty']->id,
                $toppingsByName['Blue Cheese Crumbles']->id,
                $toppingsByName['Caramelized Onions']->id,
                $toppingsByName['Lettuce']->id, // Added
                $toppingsByName['Tomato']->id, // Added
                $toppingsByName['Pickles']->id, // Added
            ]);
        }

        // #10 Quarter Pound Burger ($6.79): Base $0.50 + Quarter Pound Patty $3.00 + Lettuce $0.25 + Tomato $0.25 + Onion $0.25 = $4.25 (need $2.54 more)
        if (isset($burgerItems['#10 Quarter Pound Burger'])) {
            $burgerItems['#10 Quarter Pound Burger']->defaultAddons()->attach([
                $toppingsByName['Quarter Pound Patty']->id,
                $toppingsByName['Lettuce']->id,
                $toppingsByName['Tomato']->id,
                $toppingsByName['Onion']->id,
                $toppingsByName['Pickles']->id, // Added
                $toppingsByName['Cheddar Cheese']->id, // Added
            ]);
        }

        // #11 BBQ Bacon Burger ($7.79): Base $0.50 + Patty $2.75 + Bacon $1.50 + BBQ $0.25 + Cheese $1.00 = $6.00 (need $1.79 more)
        if (isset($burgerItems['#11 BBQ Bacon Burger'])) {
            $burgerItems['#11 BBQ Bacon Burger']->defaultAddons()->attach([
                $toppingsByName['Beef Patty']->id,
                $toppingsByName['Bacon']->id,
                $toppingsByName['BBQ Sauce']->id,
                $toppingsByName['Cheddar Cheese']->id,
                $toppingsByName['Lettuce']->id, // Added
                $toppingsByName['Tomato']->id, // Added
                $toppingsByName['Onion']->id, // Added
                $toppingsByName['Pickles']->id, // Added
            ]);
        }

        // #12 Classic Double ($8.19): Base $0.50 + Double Patty $5.50 + 4 veggies $1.00 = $7.00 (need $1.19 more)
        if (isset($burgerItems['#12 Classic Double'])) {
            $burgerItems['#12 Classic Double']->defaultAddons()->attach([
                $toppingsByName['Double Patty']->id,
                $toppingsByName['Lettuce']->id,
                $toppingsByName['Tomato']->id,
                $toppingsByName['Pickles']->id,
                $toppingsByName['Onion']->id,
                $toppingsByName['Ketchup']->id, // Added (free)
                $toppingsByName['Mustard']->id, // Added (free)
            ]);
        }
    }
}
