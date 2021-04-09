<main class="relative flex-1 overflow-y-auto focus:outline-none" tabindex="-1">
  <div class="py-8 xl:py-10">
    <div class="max-w-3xl px-4 mx-auto sm:px-6 lg:px-8 xl:max-w-5xl xl:grid xl:grid-cols-3">
      <div class="xl:col-span-2 xl:pr-8 xl:border-r xl:border-gray-200">
        <div>
          <div>
            <div class="md:flex md:items-center md:justify-between md:space-x-4 xl:border-b xl:pb-6">
              <div>
                <h1 class="text-2xl font-bold text-gray-900">{{ $chore->title }}</h1>

                <p class="mt-2 text-sm text-gray-600">
                  {{ $chore->frequency->toPrefixedString('Repeats') }}
                </p>
              </div>

              <div class="flex mt-4 space-x-3 md:mt-0">
                <a
                  href="{{ route('chores.edit', $chore) }}"
                  class="inline-flex justify-center px-4 py-2 text-sm font-medium text-indigo-700 bg-white border border-indigo-300 rounded-md shadow-sm hover:bg-indigo-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-900"
                >
                  <x-icons.pencil class="w-5 h-5 mr-2 -ml-1 text-indigo-400"/>

                  <span>Edit</span>
                </a>
                <button
                  wire:click="complete"
                  type="button"
                  class="inline-flex justify-center px-4 py-2 text-sm font-medium text-indigo-700 bg-white border border-indigo-300 rounded-md shadow-sm hover:bg-indigo-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-900"
                >
                  <x-icons.check-circle class="w-5 h-5 mr-2 -ml-1 text-indigo-400" />

                  <span>Complete</span>
                </button>
              </div>
            </div>
            <aside class="mt-8 xl:hidden">
              <x-chore-instances.next-instance :instance="$chore_instance" class="mb-6" />

              <div class="{{ $chore_instance ? 'border-t py-6' : '' }} space-y-8 border-b border-gray-200">
                <div>
                  <h2 class="text-sm font-medium text-gray-500">Owner</h2>

                  <div class="mt-3">
                    <x-users.avatar-line :user="$chore->user" />
                  </div>
                </div>
              </div>
            </aside>
            <div class="py-3 xl:pt-6 xl:pb-0">
              <h2 class="sr-only">Description</h2>

              <div class="prose max-w-none">
                {{ $chore->description }}
              </div>
            </div>
          </div>
        </div>
        <section aria-labelledby="activity-title" class="mt-8 xl:mt-10">
          <div>
            <div class="divide-y divide-gray-200">
              <div class="pb-4">
                <h2 id="activity-title" class="text-lg font-medium text-gray-900">Activity</h2>
              </div>

              <div class="pt-6">
                <!-- Activity feed-->
                <div class="flow-root">
                  <ul class="-mb-8">
                    <li>
                      <div class="relative pb-8">
                        <span class="absolute top-5 left-5 -ml-px h-full w-0.5 bg-gray-200" aria-hidden="true"></span>

                        <div class="relative flex items-start space-x-3">
                          <div class="relative">
                            <img class="flex items-center justify-center w-10 h-10 bg-gray-400 rounded-full ring-8 ring-white" src="https://images.unsplash.com/photo-1520785643438-5bf77931f493?ixlib=rb-=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=8&w=256&h=256&q=80" alt="">

                            <span class="absolute -bottom-0.5 -right-1 bg-white rounded-tl px-0.5 py-px">
                              <!-- Heroicon name: solid/chat-alt -->
                              <svg class="w-5 h-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd" d="M18 5v8a2 2 0 01-2 2h-5l-5 4v-4H4a2 2 0 01-2-2V5a2 2 0 012-2h12a2 2 0 012 2zM7 8H5v2h2V8zm2 0h2v2H9V8zm6 0h-2v2h2V8z" clip-rule="evenodd" />
                              </svg>
                            </span>
                          </div>
                          <div class="flex-1 min-w-0">
                            <div>
                              <div class="text-sm">
                                <a href="#" class="font-medium text-gray-900">Eduardo Benz</a>
                              </div>

                              <p class="mt-0.5 text-sm text-gray-500">
                                Commented 6d ago
                              </p>
                            </div>
                            <div class="mt-2 text-sm text-gray-700">
                              <p>
                                Lorem ipsum dolor sit amet, consectetur adipiscing elit. Tincidunt nunc ipsum tempor purus vitae id. Morbi in vestibulum nec varius. Et diam cursus quis sed purus nam.
                              </p>
                            </div>
                          </div>
                        </div>
                      </div>
                    </li>

                    <li>
                      <div class="relative pb-8">
                        <span class="absolute top-5 left-5 -ml-px h-full w-0.5 bg-gray-200" aria-hidden="true"></span>
                        <div class="relative flex items-start space-x-3">
                          <div>
                            <div class="relative px-1">
                              <div class="flex items-center justify-center w-8 h-8 bg-gray-100 rounded-full ring-8 ring-white">
                                <!-- Heroicon name: solid/user-circle -->
                                <svg class="w-5 h-5 text-gray-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                  <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-6-3a2 2 0 11-4 0 2 2 0 014 0zm-2 4a5 5 0 00-4.546 2.916A5.986 5.986 0 0010 16a5.986 5.986 0 004.546-2.084A5 5 0 0010 11z" clip-rule="evenodd" />
                                </svg>
                              </div>
                            </div>
                          </div>
                          <div class="min-w-0 flex-1 py-1.5">
                            <div class="text-sm text-gray-500">
                              <a href="#" class="font-medium text-gray-900">Hilary Mahy</a>
                              assigned
                              <a href="#" class="font-medium text-gray-900">Kristin Watson</a>
                              <span class="whitespace-nowrap">2d ago</span>
                            </div>
                          </div>
                        </div>
                      </div>
                    </li>

                    <li>
                      <div class="relative pb-8">
                        <span class="absolute top-5 left-5 -ml-px h-full w-0.5 bg-gray-200" aria-hidden="true"></span>
                        <div class="relative flex items-start space-x-3">
                          <div>
                            <div class="relative px-1">
                              <div class="flex items-center justify-center w-8 h-8 bg-gray-100 rounded-full ring-8 ring-white">
                                <!-- Heroicon name: solid/tag -->
                                <svg class="w-5 h-5 text-gray-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                  <path fill-rule="evenodd" d="M17.707 9.293a1 1 0 010 1.414l-7 7a1 1 0 01-1.414 0l-7-7A.997.997 0 012 10V5a3 3 0 013-3h5c.256 0 .512.098.707.293l7 7zM5 6a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd" />
                                </svg>
                              </div>
                            </div>
                          </div>
                          <div class="flex-1 min-w-0 py-0">
                            <div class="text-sm leading-8 text-gray-500">
                              <span class="mr-0.5">
                                <a href="#" class="font-medium text-gray-900">Hilary Mahy</a>
                                added tags
                              </span>
                              <span class="mr-0.5">
                                <a href="#" class="relative inline-flex items-center rounded-full border border-gray-300 px-3 py-0.5 text-sm">
                                  <span class="absolute flex items-center justify-center flex-shrink-0">
                                    <span class="h-1.5 w-1.5 rounded-full bg-rose-500" aria-hidden="true"></span>
                                  </span>
                                  <span class="ml-3.5 font-medium text-gray-900">Bug</span>
                                </a>
                                <a href="#" class="relative inline-flex items-center rounded-full border border-gray-300 px-3 py-0.5 text-sm">
                                  <span class="absolute flex items-center justify-center flex-shrink-0">
                                    <span class="h-1.5 w-1.5 rounded-full bg-indigo-500" aria-hidden="true"></span>
                                  </span>
                                  <span class="ml-3.5 font-medium text-gray-900">Accessibility</span>
                                </a>
                              </span>
                              <span class="whitespace-nowrap">6h ago</span>
                            </div>
                          </div>
                        </div>
                      </div>
                    </li>

                    <li>
                      <div class="relative pb-8">
                        <div class="relative flex items-start space-x-3">
                          <div class="relative">
                            <img class="flex items-center justify-center w-10 h-10 bg-gray-400 rounded-full ring-8 ring-white" src="https://images.unsplash.com/photo-1531427186611-ecfd6d936c79?ixlib=rb-=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=8&w=256&h=256&q=80" alt="">

                            <span class="absolute -bottom-0.5 -right-1 bg-white rounded-tl px-0.5 py-px">
                              <!-- Heroicon name: solid/chat-alt -->
                              <svg class="w-5 h-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd" d="M18 5v8a2 2 0 01-2 2h-5l-5 4v-4H4a2 2 0 01-2-2V5a2 2 0 012-2h12a2 2 0 012 2zM7 8H5v2h2V8zm2 0h2v2H9V8zm6 0h-2v2h2V8z" clip-rule="evenodd" />
                              </svg>
                            </span>
                          </div>
                          <div class="flex-1 min-w-0">
                            <div>
                              <div class="text-sm">
                                <a href="#" class="font-medium text-gray-900">Jason Meyers</a>
                              </div>
                              <p class="mt-0.5 text-sm text-gray-500">
                                Commented 2h ago
                              </p>
                            </div>
                            <div class="mt-2 text-sm text-gray-700">
                              <p>
                                Lorem ipsum dolor sit amet, consectetur adipiscing elit. Tincidunt nunc ipsum tempor purus vitae id. Morbi in vestibulum nec varius. Et diam cursus quis sed purus nam. Scelerisque amet elit non sit ut tincidunt condimentum. Nisl ultrices eu venenatis diam.
                              </p>
                            </div>
                          </div>
                        </div>
                      </div>
                    </li>
                  </ul>
                </div>
                <div class="mt-6">
                  <div class="flex space-x-3">
                    <div class="flex-shrink-0">
                      <div class="relative">
                        <img class="flex items-center justify-center w-10 h-10 bg-gray-400 rounded-full ring-8 ring-white" src="https://images.unsplash.com/photo-1517365830460-955ce3ccd263?ixlib=rb-=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=8&w=256&h=256&q=80" alt="">

                        <span class="absolute -bottom-0.5 -right-1 bg-white rounded-tl px-0.5 py-px">
                          <!-- Heroicon name: solid/chat-alt -->
                          <svg class="w-5 h-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd" d="M18 5v8a2 2 0 01-2 2h-5l-5 4v-4H4a2 2 0 01-2-2V5a2 2 0 012-2h12a2 2 0 012 2zM7 8H5v2h2V8zm2 0h2v2H9V8zm6 0h-2v2h2V8z" clip-rule="evenodd" />
                          </svg>
                        </span>
                      </div>
                    </div>
                    <div class="flex-1 min-w-0">
                      <form action="#">
                        <div>
                          <label for="comment" class="sr-only">Comment</label>
                          <textarea id="comment" name="comment" rows="3" class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-gray-900 focus:border-gray-900 sm:text-sm" placeholder="Leave a comment"></textarea>
                        </div>
                        <div class="flex items-center justify-end mt-6 space-x-4">
                          <button type="button" class="inline-flex justify-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-900">
                            <!-- Heroicon name: solid/check-circle -->
                            <svg class="w-5 h-5 mr-2 -ml-1 text-green-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                              <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                            <span>Close issue</span>
                          </button>
                          <button type="submit" class="inline-flex items-center justify-center px-4 py-2 text-sm font-medium text-white bg-gray-900 border border-transparent rounded-md shadow-sm hover:bg-black focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-900">
                            Comment
                          </button>
                        </div>
                      </form>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </section>
      </div>
      <aside class="hidden xl:block xl:pl-8">
        <x-chore-instances.next-instance :instance="$chore_instance" class="mb-6" />

        <div class="{{ $chore_instance ? 'border-t py-6' : '' }} space-y-8 border-gray-200">
          <div>
            <h2 class="text-sm font-medium text-gray-500">Owner</h2>

            <div class="mt-3">
              <x-users.avatar-line :user="$chore->user" />
            </div>
          </div>
        </div>
      </aside>
    </div>
  </div>
</main>
