
document.addEventListener("DOMContentLoaded", function () {
  const sidebarLinks = document.querySelectorAll(".side-nav ul li p");
  const content = document.getElementById("content");

  function showPage(category) {
    content.innerHTML = generateBooks(category);
    attachBorrowEvent(); // Attach event after loading books
  }

  function generateBooks(category) {
    const categoryMap = {
      Circulation: "Circulation",
      Filipiniana: "Filipiniana",
      InformationTechnology: "IT",
      IED: "IED",
      Engineering: "Engineering",
      HotelManagement: "HM",
      Agriculture: "Agriculture",
      GeneralInformation: "General_Information",
    };

    const mappedCategory = categoryMap[category] || category;

    const books = {
      Circulation: [
        {
          title: "Webster's New Collegiate Dictionary",
          author: "Merriam-Webster",
          img: "../assets/Circulation/websters_new_collegiate_dictionary.jpg",
        },
        {
          title: "A Course Module for Teaching Literacy in the Elementary Grades through Literature",
          author: "Ferdinand Bulusan, Marcelo Revibes Raqueño, Irene Blanco-Hamada, Greg Tabios Pawilen",
          img: "../assets/Circulation/teaching_literacy_elementary.jpg",
        },
        {
          title: "The World Book Encyclopedia (UV volume)",
          author: "World Book",
          img: "../assets/Circulation/world_book_encyclopedia_uv.jpg",
        },
        {
          title: "Webster's New World Dictionary with Student Handbook",
          author: "Webster",
          img: "../assets/Circulation/websters_new_world_dictionary.jpg",
        },
        {
          title: "World Book L-12",
          author: "World Book",
          img: "../assets/Circulation/world_book_l12.jpg",
        },
        {
          title: "The World Book Encyclopedia (Research Guide Index)",
          author: "World Book",
          img: "../assets/Circulation/world_book_research_guide.jpg",
        },
        {
          title: "The New Dictionary of Cultural Literacy",
          author: "E. D. Hirsch Jr., Joseph F. Kett, James Trefil",
          img: "../assets/Circulation/new_dictionary_cultural_literacy.jpg",
        },
      ],
      Filipiniana: [
        {
          title: "The Philippines Yearbook",
          author: "Generations",
          img: "../assets/Filipiniana/philippines_yearbook.jpg",
        },
        {
          title: "State Names, Seals, Flags, and Symbols",
          author: "Benjamin F. Shearer and Barbara S. Shearer",
          img: "../assets/Filipiniana/state_names_seals_flags_symbols.jpg",
        },
        {
          title: "Komunikasyon sa Akademikon Filipino (Dulog Modulayr)",
          author: "Helen E. Golloso, Gina S. Luna, Hipolito S. Ruzol, Allan Roy M. Ungriano, Magdalena O. Jocson",
          img: "../assets/Filipiniana/komunikasyon_sa_akademikon_filipino.jpg",
        },
        {
          title: "The New Philippine Almanac",
          author: "Unknown",
          img: "../assets/Filipiniana/new_philippine_almanac.jpg",
        },
      ],
      IT: [
        {
          title: "Introduction to Visual C++ 6.0 Programming",
          author: "Copernicus P. Pepito",
          img: "../assets/Information Tech/introduction_to_visual_c++6.0_programming.jpg",
        },
        {
          title: "Introduction to Database using Microsoft Access",
          author: "Dean Marmelo V. Abante",
          img: "../assets/Information Tech/introduction_to_database_using_microsoft_access.jpg",
        },
        {
          title: "Visual Basic",
          author: "Unknown",
          img: "../assets/Information Tech/visual_basic.jpg",
        },
        {
          title: "Introduction to C Programming",
          author: "Jake R. Pomperada, Kristine T. Soberano",
          img: "../assets/Information Tech/introduction_to_c_programming.jpg",
        },
        {
          title: "Fundamentals of Python Programming",
          author: "Jake R. Pomperada, Rollyn M. Moises, Sunday Vince V. Latergo",
          img: "../assets/Information Tech/fundamentals_of_python_programming.jpg",
        },
        {
          title: "Concise Encyclopedia of Computer Science",
          author: "Edwin D. Reilly",
          img: "../assets/Information Tech/concise_encyclopedia_of_computer_science.jpg",
        },
        {
          title: "Introduction to Go Programming",
          author: "Jake Rodriguez Pomperada",
          img: "../assets/Information Tech/introduction_to_go_programming.jpg",
        },
        {
          title: "Web Application Programming",
          author: "Dr. Marmelo V. Abante",
          img: "../assets/Information Tech/web_application_programming.jpg",
        },
        {
          title: "Web Programming using PHP & MySQL",
          author: "Harley V. Lampawog, Jake R. Pomperada",
          img: "../assets/Information Tech/web_programming_using_php&_mysql.jpg",
        },
        {
          title: "Introduction to HTML 5",
          author: "Copernicus P. Pepito",
          img: "../assets/Information Tech/introduction_to_html_5.jpg",
        },
      ],
      IED: [
        {
          title: "Measurement and Evaluation",
          author: "Jose F. Calderon, Expectacion C. Gonzales",
          img: "../assets/IED/measurement_and_evaluation.jpg",
        },
        {
          title: "General Chemistry 2",
          author: "Voltaire G. Organo, Dominic U. Villanueva",
          img: "../assets/IED/general_chemistry_2.jpg",
        },
        {
          title: "Assessment of Learning Outcomes (Cognitive Domain) Book I",
          author: "Danilo S. Gutierrez, Ph.D., Ma. Corazon V. Tadeña, Ph.D., Nenita P. Macatulad, Ph.D.",
          img: "../assets/IED/assessment_of_learning_outcomes(cognitive_domain)book_i.jpg",
        },
        {
          title: "Facilitating Human Learning (2nd Edition)",
          author: "Avelina M. Aquino, Ed.D.",
          img: "../assets/IED/facilitating_human_learning_(2nd_edition).jpg",
        },
        {
          title: "Action Research for Beginners in Classroom-based Contexts",
          author: "Elmer B. de Leon, LPT, DEM",
          img: "../assets/IED/action_research_for_beginners_in_classroom-based_contexts.jpg",
        },
      ],
      Engineering: [
        {
          title: "Engineering Economics for Computerized Licensure Examination (2nd Edition)",
          author: "Besavilla",
          img: "../assets/Engineering/engineering_economics_for_computerized_licensure_examination_(2nd_edition).jpg",
        },
        {
          title: "McGraw-Hill Encyclopedia of Science and Technology (Volume 4)",
          author: "McGraw-Hill",
          img: "../assets/Engineering/mcgraw-hill_encyclopedia_of_science_and_technology_(volume_4).jpg",
        },
        {
          title: "Engineering Mathematics Volume 1 (2nd Edition)",
          author: "Besavilla",
          img: "../assets/Engineering/engineering_mathematics_volume_1_(2nd_edition).jpg",
        },
        {
          title: "Solutions to Problems in Engineering Mechanics (SI Metric Edition)",
          author: "Matias A. Arreola",
          img: "../assets/Engineering/solutions_to_problems_in_engineering_mechanics_(si_metric_edition).jpg",
        },
        {
          title: "Structural Steel Design",
          author: "Besavilla",
          img: "../assets/Engineering/structural_steel_design.jpg",
        },
      ],
      HM: [
        {
          title: "Strategic Marketing (Ninth Edition)",
          author: "McGraw-Hill International Edition",
          img: "../assets/Hospitality Management/strategic_marketing_(ninth_edition).jpg",
        },
        {
          title: "Travel Manual",
          author: "Fellowship of Christians in Government, Inc.",
          img: "../assets/Hospitality Management/travel_manual.jpg",
        },
        {
          title: "Food, Water and Environmental Sanitation and Safety",
          author: "Grace Portugal-Perdigon, Virginia Serradon-Claudio, Libia de Lima-Chavez, Adela Jamorabo-Ruiz",
          img: "../assets/Hospitality Management/food_water_and_environmental_sanitation_and_safety.jpg",
        },
        {
          title: "Housekeeping Management (Revised Edition 2010)",
          author: "Amelia Samson Roldan, Amelia Malapitan Crespo",
          img: "../assets/Hospitality Management/housekeeping_management_(revised_edition_2010).jpg",
        },
        {
          title: "Experimental Cookery and Food Preservation (Second Edition)",
          author: "Eva Nebril-Flores, Ph.D.",
          img: "../assets/Hospitality Management/experimental_cookery_and_food_preservation_(second_edition).jpg",
        },
      ],
      Agriculture: [
        {
          title: "Pest Management of Rice Farmers in Asia",
          author: "K.L. Heong, M.M. Escalada",
          img: "../assets/Agriculture/pest_management_of_rice_farmers_in_asia.jpg",
        },
        {
          title: "Agribusiness Management Resource Materials Vol 1",
          author: "Unknown",
          img: "../assets/Agriculture/agribusiness_management_resource_materials_vol_1.jpg",
        },
        {
          title: "Basic Agriculture",
          author: "Bro. Manuel V. de Leon, FMS",
          img: "../assets/Agriculture/basic_agriculture.jpg",
        },
        {
          title: "Banana Production and Entrepreneurship Training Manual",
          author: "Unknown",
          img: "../assets/Agriculture/banana_production_and_entrepreneurship_training_manual.jpg",
        },
        {
          title: "Farming Handbook",
          author: "Andres H. Celestino, Marilyn S. San Pascual",
          img: "../assets/Agriculture/farming_handbook.jpg",
        },
      ],
      General_Information: [
        {
          title: "Alice in Wonderland",
          author: "Lewis Carroll",
          img: "../assets/General Information/alice_in_wonderland.jpg",
          library_id: "BL13410",
        },
        {
          title: "In Search of a Sandhill Crane",
          author: "Keith Robertson",
          illustrator: "Richard Cuffari",
          img: "../assets/General Information/in_search_of_a_sandhill_crane.jpg",
        },
        {
          title: "Sky Key",
          author: "James Frey and Nils Johnson-Shelton",
          img: "../assets/General Information/sky_key.jpg",
          note: "New York Times Bestselling Authors",
        },
        {
          title: "The High King",
          author: "Lloyd Alexander",
          img: "../assets/General Information/the_high_king.jpg",
          library_id: "BL00233",
        },
        {
          title: "The Little Shepherd of Kingdom Come",
          author: "John Fox Jr.",
          img: "../assets/General Information/the_little_shepherd_of_kingdom_come.jpg",
          library_id: "BL10858",
        },
      ],
    };

    let booksHTML = `<div class="book-container">`;

    if (books[category]) {
      books[category].forEach((book) => {
        booksHTML += `
            <div class="book">
                <img src="${book.img}" alt="${book.title}" class="book-img">
                <h3>${book.title}</h3>
                <p>${book.author}</p>
                ${book.library_id ? `<p class="library-id">ID: ${book.library_id}</p>` : ""}
                <div class="btn-group">
                    <button class="borrow">Borrow</button>
                    <button class="return" style="display:none;">Return</button>
                </div>
            </div>`;
      });
    } else {
      booksHTML += `<p>No books available for this category.</p>`;
    }

    booksHTML += `</div>`;
    return booksHTML;
  }

  function attachBorrowEvent() {
    document.querySelectorAll(".borrow").forEach((button) => {
      button.addEventListener("click", function () {
        this.style.display = "none"; // Hide "Borrow" button
        this.nextElementSibling.style.display = "inline-block"; // Show "Borrowed" button
      });
    });
  }

  sidebarLinks.forEach((link) => {
    link.addEventListener("click", function () {
      const category = this.textContent.trim();
      showPage(category);
    });
  });
});

function showMessage(message, type) {
  let messageBox = document.getElementById("messageBox");
  if (!messageBox) {
    messageBox = document.createElement("div");
    messageBox.id = "messageBox";
    document.body.appendChild(messageBox);
  }

  // Style for Glassmorphism Pop-Up
  messageBox.innerText = message;
  messageBox.style.position = "fixed";
  messageBox.style.top = "50%";
  messageBox.style.left = "50%";
  messageBox.style.transform = "translate(-50%, -50%)";
  messageBox.style.width = "300px"; // Square shape
  messageBox.style.height = "300px"; // Square shape
  messageBox.style.display = "flex";
  messageBox.style.alignItems = "center";
  messageBox.style.justifyContent = "center";
  messageBox.style.textAlign = "center";
  messageBox.style.fontSize = "18px";
  messageBox.style.fontWeight = "bold";
  messageBox.style.borderRadius = "15px"; // Soft rounded edges
  messageBox.style.backdropFilter = "blur(10px)"; // Glass effect
  messageBox.style.webkitBackdropFilter = "blur(10px)"; // Safari support
  messageBox.style.boxShadow = "0 4px 15px rgba(0,0,0,0.2)";
  messageBox.style.padding = "20px";
  messageBox.style.zIndex = "1000";
  messageBox.style.opacity = "0";
  messageBox.style.transition = "opacity 0.3s ease-in-out";

  // Kulay depende sa type (success/error)
  if (type === "success") {
    messageBox.style.background = "rgba(76, 175, 80, 0.5)"; // Green Glass Effect
    messageBox.style.color = "#fff";
    messageBox.style.border = "2px solid rgba(76, 175, 80, 0.7)";
  } else {
    messageBox.style.background = "rgba(244, 67, 54, 0.5)"; // Red Glass Effect
    messageBox.style.color = "#fff";
    messageBox.style.border = "2px solid rgba(244, 67, 54, 0.7)";
  }

  // Fade-in effect
  setTimeout(() => {
    messageBox.style.opacity = "1";
  }, 10);

  // Fade-out effect after 3 seconds
  setTimeout(() => {
    messageBox.style.opacity = "0";
    setTimeout(() => {
      messageBox.remove();
    }, 300);
  }, 3000);
}
