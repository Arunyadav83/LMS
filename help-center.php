<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UltraKey Help Center</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-50">
    <!-- Hero Section with Search -->
    <div class="relative mt-20">
        <div class="py-16 bg-gradient-to-r from-blue-600 to-cyan-400">
            <div class="container mx-auto px-4">
                <h1 class="text-4xl font-bold text-center text-white mb-8">
                    Welcome to the UltraKey Help Center
                </h1>
                <div class="max-w-2xl mx-auto relative">
                    <input 
                        type="text" 
                        id="search"
                        placeholder="Search help topics, guides, and resources"
                        class="w-full px-6 py-4 rounded-full shadow-lg border-none focus:outline-none focus:ring-2 focus:ring-blue-300 text-gray-700"
                        onkeyup="searchFunction()"
                    >
                </div>
            </div>
        </div>
    </div>

    <!-- Grid Section -->
    <div class="container mx-auto px-4 py-16">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <div onclick="navigateTo('knowledge-base')" class="bg-white rounded-xl shadow-md p-6 cursor-pointer transform transition-all duration-300 hover:scale-105 hover:shadow-xl">
                <div class="text-4xl mb-4">📚</div>
                <h2 class="text-xl font-bold text-blue-500 mb-2">Knowledge Base</h2>
                <p class="text-gray-600">Find articles and tutorials about UltraKey tools and features.</p>
            </div>

            <div onclick="navigateTo('academy')" class="bg-white rounded-xl shadow-md p-6 cursor-pointer transform transition-all duration-300 hover:scale-105 hover:shadow-xl">
                <div class="text-4xl mb-4">🎓</div>
                <h2 class="text-xl font-bold text-blue-500 mb-2">Academy</h2>
                <p class="text-gray-600">Access video training and certifications to boost your knowledge.</p>
            </div>

            <div onclick="navigateTo('live-support')" class="bg-white rounded-xl shadow-md p-6 cursor-pointer transform transition-all duration-300 hover:scale-105 hover:shadow-xl">
                <div class="text-4xl mb-4">💬</div>
                <h2 class="text-xl font-bold text-blue-500 mb-2">Live Support</h2>
                <p class="text-gray-600">Get instant assistance from our professional support team.</p>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white py-6 mt-16">
        <div class="container mx-auto px-4 text-center">
            <p>&copy; 2024 UltraKey. All rights reserved.</p>
            <a href="#" class="text-blue-400 hover:underline">Terms of Service</a> |
            <a href="#" class="text-blue-400 hover:underline">Privacy Policy</a>
        </div>
    </footer>

    <script>
        function navigateTo(section) {
            // Store the section in localStorage before navigation
            localStorage.setItem('selectedSection', section);
            
            switch (section) {
                case 'knowledge-base':
                    window.location.href = 'knowledge-base.php';
                    break;
                case 'academy':
                    window.location.href = 'academy.php';
                    break;
                case 'live-support':
                    window.location.href = 'live-support.php';
                    break;
                default:
                    console.log('Section not found');
            }
        }

        function searchFunction() {
            const input = document.getElementById('search').value.toLowerCase();
            const items = document.querySelectorAll('.grid-cols-1 > div');

            items.forEach(item => {
                const text = item.textContent.toLowerCase();
                item.style.display = text.includes(input) ? 'block' : 'none';
            });
        }

        // Check if user was redirected from another page
        window.onload = function() {
            const selectedSection = localStorage.getItem('selectedSection');
            if (selectedSection) {
                // Clear the selection after retrieving it
                localStorage.removeItem('selectedSection');
                // Scroll to the relevant section if needed
                const element = document.getElementById(selectedSection);
                if (element) {
                    element.scrollIntoView({ behavior: 'smooth' });
                }
            }
        }
    </script>
</body>
</html>