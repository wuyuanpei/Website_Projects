# Smart Notes
The link to our note taking website:
http://ec2-54-242-152-54.compute-1.amazonaws.com/CreativeProject/

Richard Wu

- Welcome to SmartNotes!
 > This is a note-taking website on which college students can write, edit, and organize notes.
 SmartNotes allows you to write notes through different modules: definitions, theorems, example problems, etc.
 The only thing you need to do is to drag different modules into your notes and fill the blanks.
 Then SmartNotes can help you organize and view notes in different ways conveniently (through the order of date, different courses and tags, etc).
 Most importantly, SmartNotes can quickly generate reviewing materials, practice tests, or cheat sheets for you to download and prepare for exams!
 SmartNotes also supports sharing notes with other students, and professors distribute notes to students.

### We have provided a test account that contains some data
- username "Richard"; password "123456"

### User Account
- The website has a portal for users to sign up and log in.
 > Click on the above link and the portal will be shown.
- Users can sign up/log in to this website through the portal with username, password and could be either "student" or "professor". The user information is then stored in database.
 > We have provided a test account that contains some data. Please log in with username "Richard" and password "123456", and you can also sign up an account.
### Dashboard
- The dashboard would by default display all the "complete notes" belonged to this user according to different "courses" as folders.
 > When you logged in with username "Richard", you will see all the "Complete Notes" organized by "courses".
- Users can also change the display category into "by dates" or "by tags" as folders.
 > You can also change the selection box into organize by "dates" or "tags". Then we will re-organize notes into different folders.
- Users can also change "complete note" into a specific "module" to display. For example, when a user in "by courses" mode changes "complete note" into "theorems", the dashboard will let the user see all the theorems in his notes of this course.
 > You can change the "Complete Notes" into "definitions", for example, then all the examples in a course (or a date/tag) will be shown by folders. Therefore, there are 5*3=15 viewing methods.
### Post New Notes
- Users can create a new note with a title, a course name, a date, and a tag.
 > You can click "Create New Note" in the dashboard and fill the blanks.
- Users can add different modules into the main body of the note.
 > You can drag different modules into your notes, and then blanks will appear for you to fill.
- Users can save the note and the modules in the note (Our server will insert them into different databases).
 > After you finished the note, click "Quit and Save this Draft". Never click "Quit and Delete this Draft", or the draft will be deleted.

### Edit Notes and Modules
- When users click on a note, the page will display all the "modules" that has already been in this note (like "definitions", "example", etc) for the user to view and edit.
 > In the dashboard "Complete Notes" mode, when you click a link of a note title, the website will display all the information related to this note to you.
- Users could edit contents in any module and even add modules to this note. Then the contents would be updated.
 > Then, you could edit the current contents in the modules or even add more modules into this note. After you finished, click "Quit and Save this Draft". Again, never click "Quit and Delete this Draft", or the draft will be deleted.
- When users click on a "module" in the dashboard, they are able to view, edit, and delete it.
 > Now, go back to the dashboard again and change "Complete Notes" into "definitions". Then when you click a link, you will see all the information in this module. You can edit and save this module after editing or even delete it.

### Different Modules
##### There are four modules users can fill so far. Each module corresponds to a database in the server. When editing or creating a note, you can drag them into the body of your note.
- "Definition" module has the blanks term{}definition{}example{}remark{} for users to fill.
- "theorem" module has the blanks statement{}proof{}application{}remark{} for users to fill.
- "problem" module has the blanks question{}answer{}remark{} for users to fill.
- Finally, "text" allows users to add plaintext into their notes.

### Generate Materials
- Users can select any necessary information for generating material, for example, date, course, tag, and what divisions in what modules they want.
 > In the dashboard, click "Generate Materials" and then you can check the checkboxs to choose what material do you want. The first three rows are selected by defaults, and you need to pick what divisions you want in different modules. For example, checked "CSE247" and all dates and tags and "term" in "definition" will let you generate all the terms in course CSE247 with any tag and date. After you finished selecting, click on "Generate the above selected contents". 
- Users can edit any information on the material through textarea after it is geneated (like edit or delete some parts).
 > After you clicked "Generating the above selected contents" you will see the contents being added to the textarea. You can then modify and delete some parts if you want. We implement "Generate Contents" as appending the data the the textarea and user can use this function to make up their material much easier.
- Users can view the material or even download it as a PDF file.
 > You can then give a name to your "pdf" file and then click "Download as pdf". If you forget to name your file, it will be named "smart_note.pdf" by default.
- The website provides some shortcuts like "cheat sheet", "practice problems".
 > You can click any shortcut and then some checkedboxes that have been set before would be checked. Then click "Generate the above contents" would let you generate the materials into the textarea.

### Share Notes
- Users can select a complete note and share it with another user if this user exists.
 > In the dashboard "Complete Notes" mode, when you click "share" near each note and then type a valid username, you can share this note with another user.

### Creative Portion
- We use React, Bootstrap, Php, MySQL, and pdf.js API.
- Users can "drag" modules into a note which is more user friendly.
- Users could choose to fold or display all the items in a folder.
- We put "today's date" as the default value for date when users create a new note.
- Users can give a name to the pdf file generated.

# About
 - Thank you so much for visiting my site.
 - Email: yuanpei.wu@wustl.edu
