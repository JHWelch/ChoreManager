<div class="lg:flex lg:h-full lg:flex-col">
  <header class="relative z-20 flex items-center justify-between px-6 py-4 border-b border-gray-200 lg:flex-none">
    <h1 class="text-lg font-semibold text-gray-900">
      <time datetime="{{ today()->isoFormat('Y-MM') }}">
        {{ today()->isoFormat('MMMM Y') }}
      </time>
    </h1>

    <div class="flex items-center">
      <div class="flex items-center rounded-md shadow-sm md:items-stretch">
        <button type="button" class="flex items-center justify-center py-2 pl-3 pr-4 text-gray-400 bg-white border border-r-0 border-gray-300 rounded-l-md hover:text-gray-500 focus:relative md:w-9 md:px-2 md:hover:bg-gray-50">
          <span class="sr-only">Previous month</span>

          <x-icons.chevron-left />

        </button>

        <button type="button" class="hidden border-t border-b border-gray-300 bg-white px-3.5 text-sm font-medium text-gray-700 hover:bg-gray-50 hover:text-gray-900 focus:relative md:block">Today</button>

        <span class="relative w-px h-5 -mx-px bg-gray-300 md:hidden"></span>

        <button type="button" class="flex items-center justify-center py-2 pl-4 pr-3 text-gray-400 bg-white border border-l-0 border-gray-300 rounded-r-md hover:text-gray-500 focus:relative md:w-9 md:px-2 md:hover:bg-gray-50">
          <span class="sr-only">Next month</span>

          <x-icons.chevron-right />

        </button>
      </div>

      <div class="hidden md:ml-4 md:flex md:items-center">
        <div class="relative">
          <button type="button" class="flex items-center py-2 pl-3 pr-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50" id="menu-button" aria-expanded="false" aria-haspopup="true">
            Month view

            <x-icons.chevron-down class="w-5 h-5 ml-2 text-gray-400" />
          </button>

          <!--
            Dropdown menu, show/hide based on menu state.

            Entering: "transition ease-out duration-100"
              From: "transform opacity-0 scale-95"
              To: "transform opacity-100 scale-100"
            Leaving: "transition ease-in duration-75"
              From: "transform opacity-100 scale-100"
              To: "transform opacity-0 scale-95"
          -->
          <div class="absolute right-0 mt-3 overflow-hidden origin-top-right bg-white rounded-md shadow-lg focus:outline-none w-36 ring-1 ring-black ring-opacity-5" role="menu" aria-orientation="vertical" aria-labelledby="menu-button" tabindex="-1">
            <div class="py-1" role="none">
              <!-- Active: "bg-gray-100 text-gray-900", Not Active: "text-gray-700" -->
              <a href="#" class="block px-4 py-2 text-sm text-gray-700" role="menuitem" tabindex="-1" id="menu-item-0">Day view</a>
              <a href="#" class="block px-4 py-2 text-sm text-gray-700" role="menuitem" tabindex="-1" id="menu-item-1">Week view</a>
              <a href="#" class="block px-4 py-2 text-sm text-gray-700" role="menuitem" tabindex="-1" id="menu-item-2">Month view</a>
              <a href="#" class="block px-4 py-2 text-sm text-gray-700" role="menuitem" tabindex="-1" id="menu-item-3">Year view</a>
            </div>
          </div>
        </div>
        <div class="w-px h-6 ml-6 bg-gray-300"></div>
        <button type="button" class="px-4 py-2 ml-6 text-sm font-medium text-white bg-indigo-600 border border-transparent rounded-md shadow-sm focus:outline-none hover:bg-indigo-700 focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">Add event</button>
      </div>

      <div class="relative ml-6 md:hidden">
        <button type="button" class="flex items-center p-2 -mx-2 text-gray-400 border border-transparent rounded-full hover:text-gray-500" id="menu-0-button" aria-expanded="false" aria-haspopup="true">
          <span class="sr-only">Open menu</span>

          <x-icons.dots-horizontal />

        </button>

        <!--
          Dropdown menu, show/hide based on menu state.

          Entering: "transition ease-out duration-100"
            From: "transform opacity-0 scale-95"
            To: "transform opacity-100 scale-100"
          Leaving: "transition ease-in duration-75"
            From: "transform opacity-100 scale-100"
            To: "transform opacity-0 scale-95"
        -->
        <div class="absolute right-0 mt-3 overflow-hidden origin-top-right bg-white divide-y divide-gray-100 rounded-md shadow-lg focus:outline-none w-36 ring-1 ring-black ring-opacity-5" role="menu" aria-orientation="vertical" aria-labelledby="menu-0-button" tabindex="-1">
          <div class="py-1" role="none">
            <!-- Active: "bg-gray-100 text-gray-900", Not Active: "text-gray-700" -->
            <a href="#" class="block px-4 py-2 text-sm text-gray-700" role="menuitem" tabindex="-1" id="menu-0-item-0">Create event</a>
          </div>
          <div class="py-1" role="none">
            <a href="#" class="block px-4 py-2 text-sm text-gray-700" role="menuitem" tabindex="-1" id="menu-0-item-1">Go to today</a>
          </div>
          <div class="py-1" role="none">
            <a href="#" class="block px-4 py-2 text-sm text-gray-700" role="menuitem" tabindex="-1" id="menu-0-item-2">Day view</a>
            <a href="#" class="block px-4 py-2 text-sm text-gray-700" role="menuitem" tabindex="-1" id="menu-0-item-3">Week view</a>
            <a href="#" class="block px-4 py-2 text-sm text-gray-700" role="menuitem" tabindex="-1" id="menu-0-item-4">Month view</a>
            <a href="#" class="block px-4 py-2 text-sm text-gray-700" role="menuitem" tabindex="-1" id="menu-0-item-5">Year view</a>
          </div>
        </div>
      </div>
    </div>
  </header>
  <div class="shadow ring-1 ring-black ring-opacity-5 lg:flex lg:flex-auto lg:flex-col">
    <div class="grid grid-cols-7 gap-px text-xs font-semibold leading-6 text-center text-gray-700 bg-gray-200 border-b border-gray-300 lg:flex-none">
      <div class="py-2 bg-white">M<span class="sr-only sm:not-sr-only">on</span></div>
      <div class="py-2 bg-white">T<span class="sr-only sm:not-sr-only">ue</span></div>
      <div class="py-2 bg-white">W<span class="sr-only sm:not-sr-only">ed</span></div>
      <div class="py-2 bg-white">T<span class="sr-only sm:not-sr-only">hu</span></div>
      <div class="py-2 bg-white">F<span class="sr-only sm:not-sr-only">ri</span></div>
      <div class="py-2 bg-white">S<span class="sr-only sm:not-sr-only">at</span></div>
      <div class="py-2 bg-white">S<span class="sr-only sm:not-sr-only">un</span></div>
    </div>
    <div class="flex text-xs leading-6 text-gray-700 bg-gray-200 lg:flex-auto">
      <div class="hidden w-full lg:grid lg:grid-cols-7 lg:grid-rows-6 lg:gap-px">
        <!--
          Always include: "relative py-2 px-3"
          Is current month, include: "bg-white"
          Is not current month, include: "bg-gray-50 text-gray-500"
        -->
        <div class="relative px-3 py-2 text-gray-500 bg-gray-50">
          <!--
            Is today, include: "flex h-6 w-6 items-center justify-center rounded-full bg-indigo-600 font-semibold text-white"
          -->
          <time datetime="2021-12-27">27</time>
        </div>
        <div class="relative px-3 py-2 text-gray-500 bg-gray-50">
          <time datetime="2021-12-28">28</time>
        </div>
        <div class="relative px-3 py-2 text-gray-500 bg-gray-50">
          <time datetime="2021-12-29">29</time>
        </div>
        <div class="relative px-3 py-2 text-gray-500 bg-gray-50">
          <time datetime="2021-12-30">30</time>
        </div>
        <div class="relative px-3 py-2 text-gray-500 bg-gray-50">
          <time datetime="2021-12-31">31</time>
        </div>
        <div class="relative px-3 py-2 bg-white">
          <time datetime="2022-01-01">1</time>
        </div>
        <div class="relative px-3 py-2 bg-white">
          <time datetime="2022-01-01">2</time>
        </div>
        <div class="relative px-3 py-2 bg-white">
          <time datetime="2022-01-03">3</time>
          <ol class="mt-2">
            <li>
              <a href="#" class="flex group">
                <p class="flex-auto font-medium text-gray-900 truncate group-hover:text-indigo-600">Design review</p>
                <time datetime="2022-01-03T10:00" class="flex-none hidden ml-3 text-gray-500 group-hover:text-indigo-600 xl:block">10AM</time>
              </a>
            </li>
            <li>
              <a href="#" class="flex group">
                <p class="flex-auto font-medium text-gray-900 truncate group-hover:text-indigo-600">Sales meeting</p>
                <time datetime="2022-01-03T14:00" class="flex-none hidden ml-3 text-gray-500 group-hover:text-indigo-600 xl:block">2PM</time>
              </a>
            </li>
          </ol>
        </div>
        <div class="relative px-3 py-2 bg-white">
          <time datetime="2022-01-04">4</time>
        </div>
        <div class="relative px-3 py-2 bg-white">
          <time datetime="2022-01-05">5</time>
        </div>
        <div class="relative px-3 py-2 bg-white">
          <time datetime="2022-01-06">6</time>
        </div>
        <div class="relative px-3 py-2 bg-white">
          <time datetime="2022-01-07">7</time>
          <ol class="mt-2">
            <li>
              <a href="#" class="flex group">
                <p class="flex-auto font-medium text-gray-900 truncate group-hover:text-indigo-600">Date night</p>
                <time datetime="2022-01-08T18:00" class="flex-none hidden ml-3 text-gray-500 group-hover:text-indigo-600 xl:block">6PM</time>
              </a>
            </li>
          </ol>
        </div>
        <div class="relative px-3 py-2 bg-white">
          <time datetime="2022-01-08">8</time>
        </div>
        <div class="relative px-3 py-2 bg-white">
          <time datetime="2022-01-09">9</time>
        </div>
        <div class="relative px-3 py-2 bg-white">
          <time datetime="2022-01-10">10</time>
        </div>
        <div class="relative px-3 py-2 bg-white">
          <time datetime="2022-01-11">11</time>
        </div>
        <div class="relative px-3 py-2 bg-white">
          <time datetime="2022-01-12" class="flex items-center justify-center w-6 h-6 font-semibold text-white bg-indigo-600 rounded-full">12</time>
          <ol class="mt-2">
            <li>
              <a href="#" class="flex group">
                <p class="flex-auto font-medium text-gray-900 truncate group-hover:text-indigo-600">Sam's birthday party</p>
                <time datetime="2022-01-25T14:00" class="flex-none hidden ml-3 text-gray-500 group-hover:text-indigo-600 xl:block">2PM</time>
              </a>
            </li>
          </ol>
        </div>
        <div class="relative px-3 py-2 bg-white">
          <time datetime="2022-01-13">13</time>
        </div>
        <div class="relative px-3 py-2 bg-white">
          <time datetime="2022-01-14">14</time>
        </div>
        <div class="relative px-3 py-2 bg-white">
          <time datetime="2022-01-15">15</time>
        </div>
        <div class="relative px-3 py-2 bg-white">
          <time datetime="2022-01-16">16</time>
        </div>
        <div class="relative px-3 py-2 bg-white">
          <time datetime="2022-01-17">17</time>
        </div>
        <div class="relative px-3 py-2 bg-white">
          <time datetime="2022-01-18">18</time>
        </div>
        <div class="relative px-3 py-2 bg-white">
          <time datetime="2022-01-19">19</time>
        </div>
        <div class="relative px-3 py-2 bg-white">
          <time datetime="2022-01-20">20</time>
        </div>
        <div class="relative px-3 py-2 bg-white">
          <time datetime="2022-01-21">21</time>
        </div>
        <div class="relative px-3 py-2 bg-white">
          <time datetime="2022-01-22">22</time>
          <ol class="mt-2">
            <li>
              <a href="#" class="flex group">
                <p class="flex-auto font-medium text-gray-900 truncate group-hover:text-indigo-600">Maple syrup museum</p>
                <time datetime="2022-01-22T15:00" class="flex-none hidden ml-3 text-gray-500 group-hover:text-indigo-600 xl:block">3PM</time>
              </a>
            </li>
            <li>
              <a href="#" class="flex group">
                <p class="flex-auto font-medium text-gray-900 truncate group-hover:text-indigo-600">Hockey game</p>
                <time datetime="2022-01-22T19:00" class="flex-none hidden ml-3 text-gray-500 group-hover:text-indigo-600 xl:block">7PM</time>
              </a>
            </li>
          </ol>
        </div>
        <div class="relative px-3 py-2 bg-white">
          <time datetime="2022-01-23">23</time>
        </div>
        <div class="relative px-3 py-2 bg-white">
          <time datetime="2022-01-24">24</time>
        </div>
        <div class="relative px-3 py-2 bg-white">
          <time datetime="2022-01-25">25</time>
        </div>
        <div class="relative px-3 py-2 bg-white">
          <time datetime="2022-01-26">26</time>
        </div>
        <div class="relative px-3 py-2 bg-white">
          <time datetime="2022-01-27">27</time>
        </div>
        <div class="relative px-3 py-2 bg-white">
          <time datetime="2022-01-28">28</time>
        </div>
        <div class="relative px-3 py-2 bg-white">
          <time datetime="2022-01-29">29</time>
        </div>
        <div class="relative px-3 py-2 bg-white">
          <time datetime="2022-01-30">30</time>
        </div>
        <div class="relative px-3 py-2 bg-white">
          <time datetime="2022-01-31">31</time>
        </div>
        <div class="relative px-3 py-2 text-gray-500 bg-gray-50">
          <time datetime="2022-02-01">1</time>
        </div>
        <div class="relative px-3 py-2 text-gray-500 bg-gray-50">
          <time datetime="2022-02-02">2</time>
        </div>
        <div class="relative px-3 py-2 text-gray-500 bg-gray-50">
          <time datetime="2022-02-03">3</time>
        </div>
        <div class="relative px-3 py-2 text-gray-500 bg-gray-50">
          <time datetime="2022-02-04">4</time>
          <ol class="mt-2">
            <li>
              <a href="#" class="flex group">
                <p class="flex-auto font-medium text-gray-900 truncate group-hover:text-indigo-600">Cinema with friends</p>
                <time datetime="2022-02-04T21:00" class="flex-none hidden ml-3 text-gray-500 group-hover:text-indigo-600 xl:block">9PM</time>
              </a>
            </li>
          </ol>
        </div>
        <div class="relative px-3 py-2 text-gray-500 bg-gray-50">
          <time datetime="2022-02-05">5</time>
        </div>
        <div class="relative px-3 py-2 text-gray-500 bg-gray-50">
          <time datetime="2022-02-06">6</time>
        </div>
      </div>
      <div class="grid w-full grid-cols-7 grid-rows-6 gap-px isolate lg:hidden">
        <!--
          Always include: "flex h-14 flex-col py-2 px-3 hover:bg-gray-100 focus:z-10"
          Is current month, include: "bg-white"
          Is not current month, include: "bg-gray-50"
          Is selected or is today, include: "font-semibold"
          Is selected, include: "text-white"
          Is not selected and is today, include: "text-indigo-600"
          Is not selected and is current month, and is not today, include: "text-gray-900"
          Is not selected, is not current month, and is not today: "text-gray-500"
        -->
        <button type="button" class="flex flex-col px-3 py-2 text-gray-500 h-14 bg-gray-50 hover:bg-gray-100 focus:z-10">
          <!--
            Always include: "ml-auto"
            Is selected, include: "flex h-6 w-6 items-center justify-center rounded-full"
            Is selected and is today, include: "bg-indigo-600"
            Is selected and is not today, include: "bg-gray-900"
          -->
          <time datetime="2021-12-27" class="ml-auto">27</time>
          <p class="sr-only">0 events</p>
        </button>
        <button type="button" class="flex flex-col px-3 py-2 text-gray-500 h-14 bg-gray-50 hover:bg-gray-100 focus:z-10">
          <time datetime="2021-12-28" class="ml-auto">28</time>
          <p class="sr-only">0 events</p>
        </button>
        <button type="button" class="flex flex-col px-3 py-2 text-gray-500 h-14 bg-gray-50 hover:bg-gray-100 focus:z-10">
          <time datetime="2021-12-29" class="ml-auto">29</time>
          <p class="sr-only">0 events</p>
        </button>
        <button type="button" class="flex flex-col px-3 py-2 text-gray-500 h-14 bg-gray-50 hover:bg-gray-100 focus:z-10">
          <time datetime="2021-12-30" class="ml-auto">30</time>
          <p class="sr-only">0 events</p>
        </button>
        <button type="button" class="flex flex-col px-3 py-2 text-gray-500 h-14 bg-gray-50 hover:bg-gray-100 focus:z-10">
          <time datetime="2021-12-31" class="ml-auto">31</time>
          <p class="sr-only">0 events</p>
        </button>
        <button type="button" class="flex flex-col px-3 py-2 text-gray-900 bg-white h-14 hover:bg-gray-100 focus:z-10">
          <time datetime="2022-01-01" class="ml-auto">1</time>
          <p class="sr-only">0 events</p>
        </button>
        <button type="button" class="flex flex-col px-3 py-2 text-gray-900 bg-white h-14 hover:bg-gray-100 focus:z-10">
          <time datetime="2022-01-02" class="ml-auto">2</time>
          <p class="sr-only">0 events</p>
        </button>
        <button type="button" class="flex flex-col px-3 py-2 text-gray-900 bg-white h-14 hover:bg-gray-100 focus:z-10">
          <time datetime="2022-01-03" class="ml-auto">3</time>
          <p class="sr-only">2 events</p>
          <div class="-mx-0.5 mt-auto flex flex-wrap-reverse">
            <div class="mx-0.5 mb-1 h-1.5 w-1.5 rounded-full bg-gray-400"></div>
            <div class="mx-0.5 mb-1 h-1.5 w-1.5 rounded-full bg-gray-400"></div>
          </div>
        </button>
        <button type="button" class="flex flex-col px-3 py-2 text-gray-900 bg-white h-14 hover:bg-gray-100 focus:z-10">
          <time datetime="2022-01-04" class="ml-auto">4</time>
          <p class="sr-only">0 events</p>
        </button>
        <button type="button" class="flex flex-col px-3 py-2 text-gray-900 bg-white h-14 hover:bg-gray-100 focus:z-10">
          <time datetime="2022-01-05" class="ml-auto">5</time>
          <p class="sr-only">0 events</p>
        </button>
        <button type="button" class="flex flex-col px-3 py-2 text-gray-900 bg-white h-14 hover:bg-gray-100 focus:z-10">
          <time datetime="2022-01-06" class="ml-auto">6</time>
          <p class="sr-only">0 events</p>
        </button>
        <button type="button" class="flex flex-col px-3 py-2 text-gray-900 bg-white h-14 hover:bg-gray-100 focus:z-10">
          <time datetime="2022-01-07" class="ml-auto">7</time>
          <p class="sr-only">1 event</p>
          <div class="-mx-0.5 mt-auto flex flex-wrap-reverse">
            <div class="mx-0.5 mb-1 h-1.5 w-1.5 rounded-full bg-gray-400"></div>
          </div>
        </button>
        <button type="button" class="flex flex-col px-3 py-2 text-gray-900 bg-white h-14 hover:bg-gray-100 focus:z-10">
          <time datetime="2022-01-08" class="ml-auto">8</time>
          <p class="sr-only">0 events</p>
        </button>
        <button type="button" class="flex flex-col px-3 py-2 text-gray-900 bg-white h-14 hover:bg-gray-100 focus:z-10">
          <time datetime="2022-01-09" class="ml-auto">9</time>
          <p class="sr-only">0 events</p>
        </button>
        <button type="button" class="flex flex-col px-3 py-2 text-gray-900 bg-white h-14 hover:bg-gray-100 focus:z-10">
          <time datetime="2022-01-10" class="ml-auto">10</time>
          <p class="sr-only">0 events</p>
        </button>
        <button type="button" class="flex flex-col px-3 py-2 text-gray-900 bg-white h-14 hover:bg-gray-100 focus:z-10">
          <time datetime="2022-01-11" class="ml-auto">11</time>
          <p class="sr-only">0 events</p>
        </button>
        <button type="button" class="flex flex-col px-3 py-2 font-semibold text-indigo-600 bg-white h-14 hover:bg-gray-100 focus:z-10">
          <time datetime="2022-01-12" class="ml-auto">12</time>
          <p class="sr-only">1 event</p>
          <div class="-mx-0.5 mt-auto flex flex-wrap-reverse">
            <div class="mx-0.5 mb-1 h-1.5 w-1.5 rounded-full bg-gray-400"></div>
          </div>
        </button>
        <button type="button" class="flex flex-col px-3 py-2 text-gray-900 bg-white h-14 hover:bg-gray-100 focus:z-10">
          <time datetime="2022-01-13" class="ml-auto">13</time>
          <p class="sr-only">0 events</p>
        </button>
        <button type="button" class="flex flex-col px-3 py-2 text-gray-900 bg-white h-14 hover:bg-gray-100 focus:z-10">
          <time datetime="2022-01-14" class="ml-auto">14</time>
          <p class="sr-only">0 events</p>
        </button>
        <button type="button" class="flex flex-col px-3 py-2 text-gray-900 bg-white h-14 hover:bg-gray-100 focus:z-10">
          <time datetime="2022-01-15" class="ml-auto">15</time>
          <p class="sr-only">0 events</p>
        </button>
        <button type="button" class="flex flex-col px-3 py-2 text-gray-900 bg-white h-14 hover:bg-gray-100 focus:z-10">
          <time datetime="2022-01-16" class="ml-auto">16</time>
          <p class="sr-only">0 events</p>
        </button>
        <button type="button" class="flex flex-col px-3 py-2 text-gray-900 bg-white h-14 hover:bg-gray-100 focus:z-10">
          <time datetime="2022-01-17" class="ml-auto">17</time>
          <p class="sr-only">0 events</p>
        </button>
        <button type="button" class="flex flex-col px-3 py-2 text-gray-900 bg-white h-14 hover:bg-gray-100 focus:z-10">
          <time datetime="2022-01-18" class="ml-auto">18</time>
          <p class="sr-only">0 events</p>
        </button>
        <button type="button" class="flex flex-col px-3 py-2 text-gray-900 bg-white h-14 hover:bg-gray-100 focus:z-10">
          <time datetime="2022-01-19" class="ml-auto">19</time>
          <p class="sr-only">0 events</p>
        </button>
        <button type="button" class="flex flex-col px-3 py-2 text-gray-900 bg-white h-14 hover:bg-gray-100 focus:z-10">
          <time datetime="2022-01-20" class="ml-auto">20</time>
          <p class="sr-only">0 events</p>
        </button>
        <button type="button" class="flex flex-col px-3 py-2 text-gray-900 bg-white h-14 hover:bg-gray-100 focus:z-10">
          <time datetime="2022-01-21" class="ml-auto">21</time>
          <p class="sr-only">0 events</p>
        </button>
        <button type="button" class="flex flex-col px-3 py-2 font-semibold text-white bg-white h-14 hover:bg-gray-100 focus:z-10">
          <time datetime="2022-01-22" class="flex items-center justify-center w-6 h-6 ml-auto bg-gray-900 rounded-full">22</time>
          <p class="sr-only">2 events</p>
          <div class="-mx-0.5 mt-auto flex flex-wrap-reverse">
            <div class="mx-0.5 mb-1 h-1.5 w-1.5 rounded-full bg-gray-400"></div>
            <div class="mx-0.5 mb-1 h-1.5 w-1.5 rounded-full bg-gray-400"></div>
          </div>
        </button>
        <button type="button" class="flex flex-col px-3 py-2 text-gray-900 bg-white h-14 hover:bg-gray-100 focus:z-10">
          <time datetime="2022-01-23" class="ml-auto">23</time>
          <p class="sr-only">0 events</p>
        </button>
        <button type="button" class="flex flex-col px-3 py-2 text-gray-900 bg-white h-14 hover:bg-gray-100 focus:z-10">
          <time datetime="2022-01-24" class="ml-auto">24</time>
          <p class="sr-only">0 events</p>
        </button>
        <button type="button" class="flex flex-col px-3 py-2 text-gray-900 bg-white h-14 hover:bg-gray-100 focus:z-10">
          <time datetime="2022-01-25" class="ml-auto">25</time>
          <p class="sr-only">0 events</p>
        </button>
        <button type="button" class="flex flex-col px-3 py-2 text-gray-900 bg-white h-14 hover:bg-gray-100 focus:z-10">
          <time datetime="2022-01-26" class="ml-auto">26</time>
          <p class="sr-only">0 events</p>
        </button>
        <button type="button" class="flex flex-col px-3 py-2 text-gray-900 bg-white h-14 hover:bg-gray-100 focus:z-10">
          <time datetime="2022-01-27" class="ml-auto">27</time>
          <p class="sr-only">0 events</p>
        </button>
        <button type="button" class="flex flex-col px-3 py-2 text-gray-900 bg-white h-14 hover:bg-gray-100 focus:z-10">
          <time datetime="2022-01-28" class="ml-auto">28</time>
          <p class="sr-only">0 events</p>
        </button>
        <button type="button" class="flex flex-col px-3 py-2 text-gray-900 bg-white h-14 hover:bg-gray-100 focus:z-10">
          <time datetime="2022-01-29" class="ml-auto">29</time>
          <p class="sr-only">0 events</p>
        </button>
        <button type="button" class="flex flex-col px-3 py-2 text-gray-900 bg-white h-14 hover:bg-gray-100 focus:z-10">
          <time datetime="2022-01-30" class="ml-auto">30</time>
          <p class="sr-only">0 events</p>
        </button>
        <button type="button" class="flex flex-col px-3 py-2 text-gray-900 bg-white h-14 hover:bg-gray-100 focus:z-10">
          <time datetime="2022-01-31" class="ml-auto">31</time>
          <p class="sr-only">0 events</p>
        </button>
        <button type="button" class="flex flex-col px-3 py-2 text-gray-500 h-14 bg-gray-50 hover:bg-gray-100 focus:z-10">
          <time datetime="2022-02-01" class="ml-auto">1</time>
          <p class="sr-only">0 events</p>
        </button>
        <button type="button" class="flex flex-col px-3 py-2 text-gray-500 h-14 bg-gray-50 hover:bg-gray-100 focus:z-10">
          <time datetime="2022-02-02" class="ml-auto">2</time>
          <p class="sr-only">0 events</p>
        </button>
        <button type="button" class="flex flex-col px-3 py-2 text-gray-500 h-14 bg-gray-50 hover:bg-gray-100 focus:z-10">
          <time datetime="2022-02-03" class="ml-auto">3</time>
          <p class="sr-only">0 events</p>
        </button>
        <button type="button" class="flex flex-col px-3 py-2 text-gray-500 h-14 bg-gray-50 hover:bg-gray-100 focus:z-10">
          <time datetime="2022-02-04" class="ml-auto">4</time>
          <p class="sr-only">1 event</p>
          <div class="-mx-0.5 mt-auto flex flex-wrap-reverse">
            <div class="mx-0.5 mb-1 h-1.5 w-1.5 rounded-full bg-gray-400"></div>
          </div>
        </button>
        <button type="button" class="flex flex-col px-3 py-2 text-gray-500 h-14 bg-gray-50 hover:bg-gray-100 focus:z-10">
          <time datetime="2022-02-05" class="ml-auto">5</time>
          <p class="sr-only">0 events</p>
        </button>
        <button type="button" class="flex flex-col px-3 py-2 text-gray-500 h-14 bg-gray-50 hover:bg-gray-100 focus:z-10">
          <time datetime="2022-02-06" class="ml-auto">6</time>
          <p class="sr-only">0 events</p>
        </button>
      </div>
    </div>
  </div>
  <div class="px-4 py-10 sm:px-6 lg:hidden">
    <ol class="overflow-hidden text-sm bg-white divide-y divide-gray-100 rounded-lg shadow ring-1 ring-black ring-opacity-5">
      <li class="flex p-4 pr-6 group focus-within:bg-gray-50 hover:bg-gray-50">
        <div class="flex-auto">
          <p class="font-semibold text-gray-900">Maple syrup museum</p>
          <time datetime="2022-01-15T09:00" class="flex items-center mt-2 text-gray-700">
            <x-icons.clock-solid class="w-5 h-5 mr-2 text-gray-400"/>

            3PM
          </time>
        </div>
        <a href="#" class="self-center flex-none px-3 py-2 ml-6 font-semibold text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm opacity-0 hover:bg-gray-50 focus:opacity-100 group-hover:opacity-100">Edit<span class="sr-only">, Maple syrup museum</span></a>
      </li>

      <li class="flex p-4 pr-6 group focus-within:bg-gray-50 hover:bg-gray-50">
        <div class="flex-auto">
          <p class="font-semibold text-gray-900">Hockey game</p>
          <time datetime="2022-01-22T19:00" class="flex items-center mt-2 text-gray-700">
            <x-icons.clock-solid class="w-5 h-5 mr-2 text-gray-400"/>

            7PM
          </time>
        </div>
        <a href="#" class="self-center flex-none px-3 py-2 ml-6 font-semibold text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm opacity-0 hover:bg-gray-50 focus:opacity-100 group-hover:opacity-100">Edit<span class="sr-only">, Hockey game</span></a>
      </li>
    </ol>
  </div>
</div>
